<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Helpers\Cart;
use App\Mail\NewOrderEmail;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function checkout(Request $request) {

        /** @var \App\Models\User $user */

        $user = $request->user();

        list($products, $cartItems) = Cart::getProductsAndCartItems();
        $orderItems = [];
        $lineItems= [];
        $totalPrice = 0;
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->title,
                        'images' => [$product->image]
                    ],
                    'unit_amount' => $product->price * 100,

                ],
                'quantity' => $quantity,
            ];
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price
            ];
        }
        // dd( route('checkout.success', [], true), route('checkout.failure', [], true));
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
          ]);

            //   create Order
          $orderData = [
            'total_price' => $totalPrice,
            'status' => OrderStatus::Unpaid,
            'created_by' => $user->id,
            'updated_by' => $user->id,
          ];
          $order = Order::create($orderData);

            //   create order Items
          foreach ($orderItems as $orderItem) {
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
          }
            //   create payment
          $paymentDate = [
            'order_id' => $order->id,
            'amount' => $totalPrice,
            'status' => PaymentStatus::Pending,
            'type' => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'session_id' => $checkout_session->id,
          ];

          Payment::create($paymentDate);
          CartItem::where('user_id', $user->id)->delete();
         return redirect($checkout_session->url);
    }

    public function success(Request $request) {
        /** @var \App\Models\User $user */

        $user = $request->user();
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
        try {
            $session_id = $request->get('session_id');
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            // $customer = \Stripe\Customer::retrieve($session->customer);
            if(!$session) {
                return view('checkout.failure', ['message'=> 'Invalid session id']);
            }
            $payment = Payment::query()->where(['session_id' =>  $session_id])->whereIn('status', [PaymentStatus::Pending, PaymentStatus::Paid])->first();
            if(!$payment) {
                throw new NotFoundHttpException();
            }

            if($payment->status === PaymentStatus::Pending->value) {
                $this->updateOrderAndSession($payment);
            }

            $customer = $session->customer_details;

            return view('checkout.success', compact('customer'));

        } catch(NotFoundHttpException $e) {
            throw $e;
        } catch(\Exception $e) {
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
        
    }

    public function failure(Request $request) {
        return view('checkout.failure', ['message' => '']);
    }

    public function checkoutOrder(Order $order, Request $request) {

         /** @var \App\Models\User $user */
         $user = $request->user();

        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
        // $session = \Stripe\Checkout\Session::retrieve($sessionId);
        $lineItems = [];

        foreach($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->title,
                        'images' => [$item->product->image]
                    ],
                    'unit_amount' => $item->unit_price * 100,

                ],
                'quantity' => $item->quantity,
            ];
        }

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
          ]);
          $order->payment->session_id = $checkout_session->id;
          $order->payment->save();

        return redirect($checkout_session->url);
    }

    public function webhook() {

        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = 'whsec_03cac92ca8617de2fe77c2015986688a2231c199e884bde099eb33567ee1ffc9';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
        } catch(\UnexpectedValueException $e) {
        // Invalid payload
            return response('', 401);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 402);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $paymentIntent = $event->data->object;
                $session_id = $paymentIntent['id'];
                $payment = Payment::query()->where(['session_id' =>  $session_id,'status' => PaymentStatus::Pending])->first();
                if($payment) {
                    $this->updateOrderAndSession($payment);
                }
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('', 200);
    }

    private function updateOrderAndSession($payment)  {
            
            $payment->status = PaymentStatus::Paid->value;
            $payment->update();

            $order = $payment->order;
            
            $order->status = OrderStatus::Paid->value;
            $order->update();

            $adminUsers = User::where('is_admin', 1)->get();

            foreach ([...$adminUsers, $order->user] as $user) {
                Mail::to($user)->send(new NewOrderEmail($order, (bool)$user->is_admin));
            }
    }
}

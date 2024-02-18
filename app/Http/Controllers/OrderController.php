<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request) {
        
        /** @var \App\Models\User $user */

        $user = $request->user();

        $orders = Order::query()->where('created_by', $user->id)->orderBy('created_at','desc')->paginate(5);

        return view('orders.index', compact('orders'));

    } 

    public function view(Order $order) {

        /** @var \App\Models\User $user */

        $user = \request()->user();

        if($order->created_by != $user->id)
        {
            return response("Your don't have persmission to view this page");
        }
        return view('orders.view', compact('order'));
    }

    

}

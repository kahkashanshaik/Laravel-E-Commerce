<?php

namespace App\Mail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderUpdateEmail extends Mailable {
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        //
    }

    public function build() {
        return $this->subject("Order Status was updated")
                    ->view("mail.update-order");
    }


}
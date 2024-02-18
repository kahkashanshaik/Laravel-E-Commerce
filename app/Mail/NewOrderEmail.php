<?php

namespace App\Mail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderEmail extends Mailable {
    use Queueable, SerializesModels;

    public function __construct(public Order $order, public $forAdmin = true) {

    }

    public function build() {
        return $this->subject("New Order ")
                    ->view("mail.new-order");
    }


}
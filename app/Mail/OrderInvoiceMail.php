<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;

class OrderInvoiceMail extends Mailable
{
    public Order $order;

    public function __construct(Order $order)
    {
        // Make sure items are loaded
        $this->order = $order->load('items', 'user');
    }

    public function build()
    {
        return $this->subject('Invoice #' . $this->order->id)
            ->view('emails.order-invoice');
    }
}

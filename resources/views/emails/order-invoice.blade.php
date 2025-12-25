@extends('emails.layout')

@section('content')
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:25px;">
        <tr>
            <td>
                <h3 style="margin:0 0 8px; color:#005662;">Billed To</h3>
                <p style="margin:0; color:#444; font-size:14px;"> {{ $order->user->name }}<br> {{ $order->user->full_name }} </p>
                
                <p style="margin:0; color:#444; font-size:14px;"> {{ $order->user->name }}<br> {{ $order->user->email }} </p>
            </td>
        </tr>
    </table> <!-- Items Table -->
    <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse;">
        <thead>
            <tr style="background:#e0f2f4;">
                <th align="left" style="border-bottom:2px solid #4fb3bf;">Item</th>
                <th align="center" style="border-bottom:2px solid #4fb3bf;">Qty</th>
                <th align="right" style="border-bottom:2px solid #4fb3bf;">Price</th>
                <th align="right" style="border-bottom:2px solid #4fb3bf;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <?php
                $modelClass = $item->model;
                $product = $modelClass::find($item->item_id);
                $type = str_replace('App\\Models\\', '', $item->model);
                ?>
                <tr>
                    <td style="border-bottom:1px solid #eee;">
                        {{ $product ? $type . ': ' . $product->title . '. ' . ($type == 'Lesson' ? '(Course: ' . $product->course->title . ')' : '') : 'Item #' . $item->item_id }}
                    </td>
                    <td align="center" style="border-bottom:1px solid #eee;"> {{ $item->quantity }} </td>
                    <td align="right" style="border-bottom:1px solid #eee;"> ${{ number_format($item->total, 2) }} </td>
                    <td align="right" style="border-bottom:1px solid #eee;">
                        ${{ number_format($item->total * $item->quantity, 2) }} </td>
                </tr>
            @endforeach
        </tbody>
    </table> <!-- Totals -->
    <table width="100%" cellpadding="8" cellspacing="0" style="margin-top:25px;">
        <tr>
            <td align="right" style="color:#555;">Subtotal:</td>
            <td align="right" width="120"> <strong>${{ number_format($order->subtotal, 2) }}</strong> </td>
        </tr>
        <tr>
            <td align="right" style="color:#555;">Tax:</td>
            <td align="right"> <strong>${{ number_format($order->tax, 2) }}</strong> </td>
        </tr>
        <tr>
            <td align="right" style="font-size:16px; color:#005662;"> <strong>Grand Total:</strong> </td>
            <td align="right" style="font-size:16px; color:#005662;">
                <strong>${{ number_format($order->total, 2) }}</strong>
            </td>
        </tr>
    </table>
@endsection

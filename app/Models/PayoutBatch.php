<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutBatch extends Model
{
    use HasFactory;
    protected $fillable = [
        'educator_id',
        'payment_ids', // ids of all the payments in this batch, implode with comma
        'status', // pending, processing, completed, failed, cancelled
        'start_date',// date from when the payments are started, payment table
        'end_date',//last date of the payments, payment table
        'total_amount', // total releasing amount
        'total_commission', // just put it as 0 for now , as comission is deducted from the total amount in the payment table
        'total_net_amount', // total net amount after comission deduction
        'currency', // currency of the payments
        'notes', // notes for the payout batch
        'description', // description for the payout batch
        'stripe_response', // stripe response for the payout batch
        'processed_by', // who processed the payout batch
        'processed_at', // date when the payout batch was processed
    ];

    public function educator()
    {
        return $this->belongsTo(User::class, 'educator_id');
    }
    //cast payment_ids as array
    protected $casts = [
        'payment_ids' => 'string',
        'start_date'  => 'datetime',
        'end_date'    => 'datetime',
        'processed_at'=> 'datetime',
    ];

    //add on payments , explode payment_ids and get the payments
    public function payments()
    {
        $paymentIds = is_array($this->payment_ids) ? $this->payment_ids : explode(',', $this->payment_ids);
        return Payment::whereIn('id', $paymentIds)->get();
    }
}

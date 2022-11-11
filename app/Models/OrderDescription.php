<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'reference',
        'payment_method',
        'reference_number'.
        'address',
        'shipping_fee',
        'status'
    ];

    protected $appends = [
        'orders',
        'customer_name',
        'amount',
        'display_status'
    ];

    protected $with = [
    ];

    public function getOrdersAttribute()
    {
        $orders = Order::where('reference', $this->reference)->get();

        return $orders;
    }

    public function getAmountAttribute()
    {
        return $this->orders->sum('amount');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCustomerNameAttribute()
    {
        return $this->user->name;
    }

    public function getDisplayStatusAttribute()
    {
        $status = $this->status;

        if($status == 'to_process') {
            return 'Processing';
        }

        if($status == 'to_deliver') {
            return 'For Delivery';
        }

        if($status == 'to_receive') {
            return 'To Receive';
        }

        return null;
    }
}

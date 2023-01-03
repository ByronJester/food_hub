<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OrderDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'reference',
        'payment_method',
        'address',
        'shipping_fee',
        'status'
    ];

    protected $appends = [
        'orders',
        'customer_name',
        'amount',
        'display_status',
        'total',
        'contact',
        'date'
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

    public function getTotalAttribute()
    {
        return $this->orders->sum('amount') + $this->shipping_fee;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCustomerNameAttribute()
    {
        return $this->user->name;
    }

    public function getContactAttribute()
    {
        return $this->user->phone;
    }

    public function getDateAttribute()
    {
        $date = Carbon::parse($this->created_at);

        return $date->isoFormat('LLL'); 
    }

    public function getDisplayStatusAttribute()
    {
        $status = $this->status;

        if($status == 'cancel') {
            return 'Cancelled';
        }

        if($status == 'pending') {
            return 'Pending';
        }

        if($status == 'to_process') {
            return 'Preparing';
        }

        if($status == 'to_deliver') {
            return 'For Delivery';
        }

        if($status == 'to_receive') {
            return 'To Receive';
        }

        if($status == 'reported') {
            return 'Bogus Order';
        }

        if($status == 'received') {
            return 'Order Received';
        }

        return null;
    }
}

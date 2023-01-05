<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'product_id',
        'user_id',
        'amount',
        'quantity',
        'status',
        'is_delivered',
        'reference',
        'payment_method'
    ];

    protected $appends = [
        'food_hub',
        'display_date'
    ];

    protected $with = [
        'product',
        'restaurant',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function getFoodHubAttribute()
    {   
        if($this->restaurant) {
            return $this->restaurant->restaurant_name;
        }
        
        return null;
        
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getDisplayDateAttribute()
    {
        $date = Carbon::parse($this->create_at);

        return $date->isoFormat('LLL'); 
    }
}

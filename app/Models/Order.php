<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $appends = [
        'food_hub'
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
}

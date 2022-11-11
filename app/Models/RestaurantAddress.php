<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantAddress extends Model
{
    use HasFactory;

    protected $table = 'restaurant_address';

    protected $fillable = [
        'restaurant_id',
        'address'
    ];

    protected $with = [
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}

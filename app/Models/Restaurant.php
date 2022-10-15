<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_name',
        'image',
        'banner',
        'is_active',
    ];

    protected $with = [
        'products'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

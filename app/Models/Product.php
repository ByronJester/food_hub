<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'name',
        'amount',
        'image',
        'category',
        'is_active',
        'description'
    ];

    // protected $casts = [ 
    //     'amount' => 'float'
    // ];

    public function getDescriptionAttribute($value)
    {
        if(!$value || $value == null || $value == 'null') {
            return 'None';
        }

        return $value;
    }
}

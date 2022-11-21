<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_name',
        'image',
        'banner',
        'is_active',
        'permit'
    ];

    protected $with = [
        'products',
        'places'
    ];

    protected $appends = [
        'phone'
    ];

    public function products()
    {   
        $auth = Auth::user();

        $data = $this->hasMany(Product::class);

        if($auth) {
            if($auth->role == 3){
                $data = $data->where('is_active', 1);
            }
        }

        if(!$auth) {
            $data = $data->where('is_active', 1);
        }

        return $data;
        
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function places()
    {
        return $this->hasMany(RestaurantAddress::class);
    }

    public function getPhoneAttribute()
    {
        return $this->user->phone;
    }

}

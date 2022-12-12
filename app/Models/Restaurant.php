<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        'phone',
        'address',
        'opening',
        'closing',
        'lock'
    ];

    public function products()
    {   
        $auth = Auth::user();

        $data = $this->hasMany(Product::class);

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

    public function getAddressAttribute()
    {
        return $this->user->address;
    }

    public function getOpeningAttribute()
    {
        return Carbon::parse($this->opening_time)->format('g:i A');
    }

    public function getClosingAttribute()
    {
        return Carbon::parse($this->closing_time)->format('g:i A');
    }

    public function getLockAttribute()
    {
        if(time() >= strtotime(date('Y-m-d'). ' ' . $this->opening_time) && time() <= strtotime(date('Y-m-d'). ' ' . $this->closing_time)) {
            return false;
        } else {
            return true;
        }
    }
    

}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'phone',
        'email',
        'password',
        'user_type',
        'role',
        'is_active',
        'picture_id',
        'reference',
        'address'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'verified',
        'wallet',
        'permit',
        'trays'
    ];

    public function getVerifiedAttribute()
    {
        return $this->is_active ? 'Verified' : 'Not Verified';
    }

    public function getPictureIdAttribute($value)
    {
        $data = json_decode($value);

        return explode(",", $data);
    }

    public function getWalletAttribute()
    {
        $amount = 0;

        if($this->role == 2 && $this->user_type == 'owner') {
            $restaurant = Restaurant::where('user_id', $this->id)->first();

            $orderDescriptions = OrderDescription::where('restaurant_id', $restaurant->id)->where('payment_method', 'gcash')
                ->whereNotIn('status', ['pending'])
                ->get();

            foreach($orderDescriptions as $description) {
                $amount += $description->shipping_fee;

                $orders = Order::where('reference', $description->reference)->get();

                $amount += $orders->sum('amount');
            }
        }
        

        return $amount;
    }

    public function getPermitAttribute()
    {
        $restaurant = Restaurant::where('user_id', $this->id)->first();

        if(!$restaurant) return null;

        return $restaurant->permit;
    }

    public function getTraysAttribute()
    {   
        $count = 0;

        if($this->role == 2) {
            $restaurant = null;

            if($this->user_type == 'staff') {
                $owner = User::where('reference', $this->reference)->where('user_type', 'owner') ->first();

                $restaurant = Restaurant::where('user_id', $owner->id)->first();

            } else {
                $restaurant = Restaurant::where('user_id', $this->id)->first();
            }
            

            $count = OrderDescription::where('restaurant_id', $restaurant->id)->where('status', 'to_process')->count();
        } else {
            if($this->role == 3) {
                $count = OrderDescription::where('user_id', $this->id)->where('status', 'to_receive')->count();
            }
        }

        return $count;
    }

}

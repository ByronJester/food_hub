<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
    public function viewRestaurant(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            return Inertia::render('Restaurant', [
                'auth'    => $auth,
                'options' => [
                    'restaurant' => Restaurant::where('user_id', $auth->id)->first()
                ]
            ]);
        }

        return redirect('/');
    }

    public function viewRestaurants(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            return Inertia::render('FoodHubs', [
                'auth'    => $auth,
                'options' => [
                    'restaurants' => Restaurant::where('is_active', true)->get()
                ]
            ]);
        }

        return redirect('/');
    }

    public function createProduct(Request $request)
    {
        $rules = [
            'name' => "required|string",
            'amount' => "required|numeric",
            'restaurant_id' => "required", 
            'category' => "required",
            'image' => "required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        $data = $request->except(['image']);

        if($image = $request->image) {
            
            $path = public_path().'/images/uploads';

            $filename = time() . '_' . Str::random(8);

            $extension = $image->getClientOriginalExtension();
            
            $uplaod = $image->move($path, $filename . '.' . $extension);

            $data['image'] = $filename . '.' . $extension;
        }

        if($request->id) {
            $save = Product::where('id', $request->id)->update($data);
        } else {
            $save = Product::create($data);
        }
        

        return response()->json(['status' => 200], 200);  
    }
}

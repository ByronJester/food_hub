<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\Product;
use App\Models\RestaurantAddress;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderDescription;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;

class RestaurantController extends Controller
{
    public function viewRestaurant(Request $request)
    {
        $auth = Auth::user();
        
        if($auth) {
            $owner = null;

            $restaurant = Restaurant::where('user_id', $auth->id)->first();

            if($auth->user_type == 'staff' && !$restaurant) {
                $owner = User::where('reference', $auth->reference)->where('user_type', 'owner')->first();
                $restaurant = Restaurant::where('user_id', $owner->id)->first();
            }
            
            $address = RestaurantAddress::where('restaurant_id', $restaurant->id)->get();
            
            return Inertia::render('Restaurant', [
                'auth'    => $auth,
                'options' => [
                    'restaurant' => $restaurant,
                    'address' => $address,
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
            'description' => "nullable"
        ];

        if($request->id) {
            $rules = [
                'name' => "required|string",
                'amount' => "required|numeric",
                'restaurant_id' => "required", 
                'category' => "required",
                'description' => "nullable"
            ];
        }

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

    public function createAddress(Request $request)
    {
        $rules = [
            'restaurant_id' => "required",
            'address' => "required|string",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        RestaurantAddress::create($request->toArray());

        return response()->json(['status' => 200], 200);  
    }

    public function removeAddress(Request $request)
    {
        RestaurantAddress::where('id', $request->id)->delete();

        return response()->json(['status' => 200], 200);  
    }

    public function viewTrays(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            $owner = null;

            $restaurant = Restaurant::where('user_id', $auth->id)->first();
            
            if($auth->user_type == 'staff' && !$restaurant) {
                $owner = User::where('reference', $auth->reference)->where('user_type', 'owner')->first();
                $restaurant = Restaurant::where('user_id', $owner->id)->first();
            }

            $maxDays = date('t');

            $days = [
                1, 2, 3, 4, 5, 6, 7, 8, 9, 10,
                11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
                21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31
            ];

            $sales = [];

            if($maxDays != count($days)) {
                $days = [
                    1, 2, 3, 4, 5, 6, 7, 8, 9, 10,
                    11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
                    21, 22, 23, 24, 25, 26, 27, 28, 29, 30
                ];
            }

            foreach($days as $day) {
                $sale = Order::whereNotIn('status', ['pending'])->whereMonth('created_at', Carbon::now()->month)->whereDay('created_at', $day)->get();

                array_push($sales, $sale->sum('amount'));
            }

            $orders = OrderDescription::orderBy('created_at', 'desc')->where('restaurant_id', $restaurant->id)->get();
            
            return Inertia::render('RestaurantTrays', [
                'auth'    => $auth,
                'options' => [
                    'restaurant' => $restaurant,
                    'orders' => $orders,
                    'days' => $days,
                    'sales' => $sales
                ]
            ]);
        }

        return redirect('/');
    }

    public function changeImage(Request $request)
    {
        if($image = $request->image) {
            
            $path = public_path().'/images/uploads';

            $filename = time() . '_' . Str::random(8);

            $extension = $image->getClientOriginalExtension();
            
            $uplaod = $image->move($path, $filename . '.' . $extension);

            $image = $filename . '.' . $extension;

            Restaurant::where('id', $request->id)->update([
                'image' => $image
            ]);
        }

        if($banner = $request->banner) {
        
            $path = public_path().'/images/uploads';

            $filename = time() . '_' . Str::random(8);

            $extension = $banner->getClientOriginalExtension();
            
            $uplaod = $banner->move($path, $filename . '.' . $extension);

            $banner = $filename . '.' . $extension;

            Restaurant::where('id', $request->id)->update([
                'banner' => $banner
            ]);

        }

        return response()->json(['status' => 200], 200);  
    }

    public function reactivateDeactivateProduct(Request $request)
    {
        Product::where('id', $request->id)->update([
            'is_active' => $request->is_active
        ]);

        return redirect()->back();  
    }

    public function saveTime(Request $request)
    {
        $auth = Auth::user();

        $restaurant = Restaurant::where('user_id', $auth->id)->first();
        $restaurant->opening_time = $request->opening_time;
        $restaurant->closing_time = $request->closing_time;
        $restaurant->save();

        return response()->json(['status' => 200], 200); 
    }

    public function viewReports(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            $owner = null;

            $restaurant = Restaurant::where('user_id', $auth->id)->first();
            
            if($auth->user_type == 'staff' && !$restaurant) {
                $owner = User::where('reference', $auth->reference)->where('user_type', 'owner')->first();
                $restaurant = Restaurant::where('user_id', $owner->id)->first();
            }

            $orders = OrderDescription::orderBy('created_at', 'desc')->where('restaurant_id', $restaurant->id)->get();
            
            return Inertia::render('Reports', [
                'auth'    => $auth,
                'options' => [
                    'restaurant' => $restaurant,
                    'orders' => $orders,
                ]
            ]);
        }

        return redirect('/');
    }
}

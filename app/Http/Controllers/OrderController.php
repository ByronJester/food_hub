<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\RestaurantAddress;
use App\Models\OrderDescription;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class OrderController extends Controller
{
    public function viewOrders(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            $orders = Order::where('user_id', $auth->id)->where('status', 'pending')
                ->whereHas('product', function (Builder $query) {
                    $query->where('is_active', 1);
                })
                ->get();

            return Inertia::render('CustomerOrders', [
                'auth'    => $auth,
                'options' => [
                    'orders' => $orders->groupBy('food_hub')
                ]
            ]);
        }

        return redirect('/');
    }

    public function changeActiveTab(Request $request)
    {
        $auth = Auth::user();

        $orders = Order::where('user_id', $auth->id)->where('status', $request->status)->get();

        return response()->json(['status' => 200, 'orders' => $orders->groupBy('food_hub')], 200); 
    }

    public function placeOrder(Request $request)
    {
        $auth = Auth::user();

        $product = Product::where('id', $request->product_id)->first();

        $order = Order::where('user_id', $auth->id)->where('product_id', $request->product_id)->where('status', 'pending')->first();

        $restaurant = Restaurant::where('id', $product->restaurant_id)->first();

        if($order) {
            $quantity = $request->quantity + $order->quantity;

            $order->quantity = $quantity;
            $order->amount = $product->amount * $quantity;

            $order->save();
        } else {
            $data = $request->toArray();

            $data['user_id'] = $auth->id;
            $data['amount'] = $product->amount * $request->quantity;
            $data['restaurant_id'] = $restaurant->id;

            Order::create($data);
        }

        
        return response()->json(['status' => 200], 200);  
    }

    public function changeQuantity(Request $request)
    {   
        $auth = Auth::user();

        $order = Order::where('id', $request->id)->first();
        $product = Product::where('id', $request->product_id)->first();

        $order->quantity = $request->quantity;
        $order->amount = $request->quantity * $product->amount;

        $order->save();

        $orders = Order::where('user_id', $auth->id)->get();

        return response()->json(['status' => 200, 'orders' => $orders->groupBy('food_hub')], 200);  
    }

    public function removeOrder(Request $request)
    {
        $auth = Auth::user();

        $order = Order::where('id', $request->id)->delete();

        $orders = Order::where('user_id', $auth->id)->get();

        return response()->json(['status' => 200, 'orders' => $orders->groupBy('food_hub')], 200);  
    }

    public function checkoutOrder(Request $request) 
    {
        $auth = Auth::user();

        $rules = [
            'payment_method' => "required|string",
            'reference_number' => "required_if:payment_method,gcash|nullable|numeric",
            'address' => "required_if:otherAddress,true|nullable|string",
            'orders' => "required"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        $orders = collect($request->orders);

        $orders = $orders->pluck('id');

        $carts = Order::whereIn('id', $orders)->get();

        $amount = $carts->sum('amount') + 60;

        $publicKey = $this->getPublicKey("pk_test_CP2M2yoCiYczwJkCYnPprYMZ");
        $secretKey = $this->getSecretKey("sk_test_1JsM89U7BvfBFsifjRwGJ9nx");

        $source = $this->createSource($amount, $publicKey, $secretKey);

        $paymentInfo = $this->createPayment();

        

        $reference = Str::random(10);

        $orders = collect($request->orders);

        $orders = $orders->pluck('id');

        Order::whereIn('id', $orders)->update([
            'status' => 'to_process',
            'reference' => $reference
        ]);

        $data = $request->only(['payment_method', 'reference_number', 'user_id']);

        $restaurant = Restaurant::where('restaurant_name', $request->food_joint)->first();

        $data['restaurant_id'] = $restaurant->id;
        $data['reference'] = $reference;
        $data['shipping_fee'] = 60;
        $data['status'] = 'to_process';

        if($request->otherAddress) {
            $data['address'] = $request->address;
        } else {
            $data['address'] = $auth->address;
        }

        OrderDescription::forceCreate($data);

        Order::where('user_id', $auth->id)->get();

        return response()->json(['status' => 200, 'data' => $data, 'url' => $source->redirect->checkout_url], 200);  
    }

    public function getAddress(Request $request)
    {
        $restaurant = Restaurant::where('restaurant_name', 'LIKE',  $request->address)->first();

        $user = User::where('id', $restaurant->user_id)->first();

        $places = RestaurantAddress::where('restaurant_id', $restaurant->id)->get();

        return response()->json(['status' => 200, 'places' => $places, 'phone' => $user->phone], 200); 
    }

    public function changeStatus(Request $request)
    {
        Order::where('reference', $request->reference)->update([
            'status' => $request->status
        ]);

        OrderDescription::where('reference', $request->reference)->update([
            'status' => $request->status
        ]);

        $user = User::where('id', $request->user_id)->first();

        $message = null;

        if($request->status == 'to_deliver') {
            $message = 'Your order is ready for delivery.';
        } else {
            $message = 'Your order arrived at your location.';
        }

        $sms = $this->sendSms($user->phone, $message);
 
        return response()->json(['status' => 200, 'sms' => $sms], 200); 
    }

    public function buyNow(Request $request)
    {
        $auth = Auth::user();
        
        $rules = [
            'payment_method' => "required|string",
            'reference_number' => "required_if:payment_method,gcash|nullable|numeric",
            'address' => "required_if:otherAddress,true|nullable|string",
            'order' => "required"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        $reference = Str::random(10);

        $order = (object) $request->order;

        $product = Product::where('id', $order->product_id)->first();

        // return $order;

        Order::create([
            'restaurant_id' => $request->restaurant_id,
            'product_id' => $order->product_id,
            'user_id' => $auth->id,
            'amount' => $product->amount * $order->quantity,
            'quantity' => $order->quantity,
            'status' => 'to_process',
            'reference' => $reference

        ]);

        $data = $request->only(['payment_method', 'reference_number']);


        $data['restaurant_id'] = $request->restaurant_id;
        $data['reference'] = $reference;
        $data['shipping_fee'] = 60;
        $data['status'] = 'to_process';
        $data['user_id'] = $auth->id;

        if($request->otherAddress) {
            $data['address'] = $request->address;
        } else {
            $data['address'] = $auth->address;
        }

        OrderDescription::forceCreate($data);

        $orders = Order::where('user_id', $auth->id)->get();

        return response()->json(['status' => 200, 'data' => $data], 200);
    }

    public function orderReceived(Request $request)
    {
        Order::where('reference', $request->reference)->update(['status' => 'received']);

        OrderDescription::where('reference', $request->reference)->update(['status' => 'received']);

        return response()->json(['status' => 200], 200);
    }

    public function successPage(Request $request)
    {
        $auth = Auth::user();

        if($auth) {

            return Inertia::render('Success', [
                'auth'    => $auth,
                'options' => [
                ]
            ]);
        }

        return redirect('/');
    }

    public function failedPage(Request $request)
    {
        $auth = Auth::user();

        if($auth) {

            return Inertia::render('Failed', [
                'auth'    => $auth,
                'options' => [
                ]
            ]);
        }

        return redirect('/');
    }
}

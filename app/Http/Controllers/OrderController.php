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

    public function getGeoCode($address)
    {
        // $key = 'AIzaSyDCGS5UZzxRuFzBXmQ-6wXEnXn9dO8pEkE';
        $key = 'hJqRLbgHIo29NdQ2CESAH8lDNN96vJ3E';

        // $url = "http://maps.google.com/maps/api/geocode/json?address=$address&key=$key";
        $url = "https://api.tomtom.com/search/2/geocode/$address.json?key=$key";

        // $geocode = file_get_contents($url);
        $json = json_decode($url);
        // $data['lat'] = $json->results[0]->geometry->location->lat;
        // $data['lng'] = $json->results[0]->geometry->location->lng;
        return $json;
    }

    public function viewOrders(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            $orders = Order::where('user_id', $auth->id)->where('status', 'oncart')
                ->whereHas('product', function (Builder $query) {
                    $query->where('is_active', 1);
                })
                ->get();

            return Inertia::render('CustomerOrders', [
                'auth'    => $auth,
                'options' => [
                    'orders' => $orders->groupBy('food_hub'),
                    'tray' => Order::where('status', 'oncart')->where('user_id', $auth->id)->count(),
                    'pending' => Order::where('status', 'pending')->where('user_id', $auth->id)->count(),
                    'processing' => Order::where('status', 'to_process')->where('user_id', $auth->id)->count(),
                    'deliver' => Order::where('status', 'to_deliver')->where('user_id', $auth->id)->count(),
                    'receive' => Order::where('status', 'to_receive')->where('user_id', $auth->id)->count(),
                    // 'coordinates' => $coordinates
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

        $order = Order::where('user_id', $auth->id)->where('product_id', $request->product_id)->where('status', 'oncart')->first();

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
            $data['status'] = 'oncart';

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

        $selectedOrder = Order::where('id', $request->id)->first();

        if($request->reference) {
            OrderDescription::where('reference', $request->reference)->delete();
        }

        return response()->json(['status' => 200, 'orders' => $orders->groupBy('food_hub')], 200);  
    }

    public function checkoutOrder(Request $request) 
    {
        $auth = Auth::user();

        $rules = [
            'payment_method' => "required|string",
            'address' => "required_if:otherAddress,true|nullable|string",
            'orders' => "required"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        $url = null;

        $orders = collect($request->orders);

        $orders = $orders->pluck('id');

        $carts = Order::whereIn('id', $orders)->get();

        $amount = $carts->sum('amount') + 60;

        $description = $request->only(['payment_method', 'user_id']);
    
        $restaurant = Restaurant::where('restaurant_name', $request->food_joint)->first();

        $address = $request->otherAddress ? $request->address : $auth->address;

        $payment_method = $request->payment_method;

        if($request->payment_method == 'cod') {
            $reference = Str::random(10);

            Order::whereIn('id', $orders)->update([
                'status' => 'pending',
                'reference' => $reference,
                'payment_method' => $payment_method
            ]);
    
            $description['restaurant_id'] = $restaurant->id;
            $description['reference'] = $reference;
            $description['shipping_fee'] = 60;
            $description['status'] = 'pending';
            $description['address'] = $address;
            $description['payment_method'] = $payment_method;
    
            OrderDescription::forceCreate($description);
        } else {
            $amount = $amount * 100;

            $publicKey = $this->getPublicKey($restaurant->pk); 
            $secretKey = $this->getSecretKey($restaurant->sk);

            $source = $this->createSource($amount, $publicKey, $secretKey);
            
            $url = $source->redirect->checkout_url;

            session()->put('orders', $orders);
            session()->put('description', $description);
            session()->put('restaurant_id', $restaurant->id);
            session()->put('address', $address);
            session()->put('payment_method', $payment_method);
        }

        return response()->json(['status' => 200, 'data' => [], 'url' => $url], 200);   
    }

    public function buyNow(Request $request)
    {
        $auth = Auth::user();
        
        $rules = [
            'payment_method' => "required|string",
            'address' => "required_if:otherAddress,true|nullable|string",
            'order' => "required"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        $order = (object) $request->order;
        $product = Product::where('id', $order->product_id)->first();

        $url = null;

        $address = !!$request->otherAddress ? $request->address : $auth->address;
        $payment_method = $request->payment_method;

        if($request->payment_method == 'cod') {
            $reference = Str::random(10);

            $orderSave = Order::forceCreate([
                'restaurant_id' => $request->restaurant_id,
                'product_id' => $order->product_id,
                'user_id' => $auth->id,
                'amount' => $product->amount * $order->quantity,
                'quantity' => $order->quantity,
                'status' => 'pending',
                'reference' => $reference,
                'payment_method' => $payment_method
            ]);
            
            $description['user_id'] = $auth->id;
            $description['restaurant_id'] = $request->restaurant_id;
            $description['reference'] = $reference;
            $description['shipping_fee'] = 60;
            $description['status'] = 'pending';
            $description['address'] = $address;
            $description['payment_method'] = $payment_method;
    
            OrderDescription::forceCreate($description);
        } else {
            
            $orderSave = Order::create([
                'restaurant_id' => $request->restaurant_id,
                'product_id' => $order->product_id,
                'user_id' => $auth->id,
                'amount' => $product->amount * $order->quantity,
                'quantity' => $order->quantity,
                'status' => 'oncart',
                'reference' => null
            ]);

            $amount = ($product->amount * $order->quantity) + 60;
            $amount = $amount * 100;

            $restaurant = Restaurant::where('id', $request->restaurant_id)->first();

            $publicKey = $this->getPublicKey($restaurant->pk);
            $secretKey = $this->getSecretKey($restaurant->sk);

            $source = $this->createSource($amount, $publicKey, $secretKey);
            
            $url = $source->redirect->checkout_url;

            $description = [];
            $description['payment_method'] = $request->payment_method;
            $description['user_id'] = $auth->id;

            session()->put('orders', [ $orderSave->id ]);
            session()->put('description', $description);
            session()->put('restaurant_id', $request->restaurant_id);
            session()->put('address', $address);
            session()->put('payment_method', $payment_method);
        }

        return response()->json(['status' => 200, 'data' => [], 'url' => $url], 200);
    }

    public function getAddress(Request $request)
    {
        $restaurant = Restaurant::where('restaurant_name', 'LIKE',  $request->address)->first();

        $user = User::where('id', $restaurant->user_id)->first();

        return response()->json(['status' => 200, 'address' => $user->address, 'phone' => $user->phone], 200); 
    }

    public function changeStatus(Request $request)
    {
        $rules = [
            'reason' => "required_if:status,cancel|nullable|string",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        $data = [];
        $data = $request->only(['status']);

        if(!!$request->reason) {
            $data = $request->only(['status', 'reason']);
        }

        Order::where('reference', $request->reference)->update($data);

        OrderDescription::where('reference', $request->reference)->update([
            'status' => $request->status
        ]);

        $user = User::where('id', $request->user_id)->first();

        $message = null;

        if($request->status == 'to_deliver') {
            $message = 'Your order is ready for delivery.';
        } else {
            if($request->status == 'cancel') {
                $message = 'Your order is cancelled.';
            } else if($request->status == 'to_process') {
                $message = 'Your order is preparing.';
            } else {
                if($request->status == 'to_receive') {
                    $message = 'Your order arrived at your location.';
                } else {
                    $message = 'You are mark as a bogus buyer.';
                }
                
            }
        }

        $sms = $this->sendSms($user->phone, $message);
    
        return response()->json(['status' => 200, 'sms' => $sms], 200); 
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

        $source = session()->get('source');
        $orderId = session()->get('orders');
        $description = session()->get('description');
        $restaurant_id = session()->get('restaurant_id');
        $address = session()->get('address');
        $payment_method = session()->get('payment_method');

        $orders = Order::whereIn('id', $orderId)->get();

        $paymentInfo = $this->createPayment();

        if(!!$paymentInfo && $paymentInfo == 'paid') {
            $reference = Str::random(10);

            Order::whereIn('id', $orderId)->update([
                'status' => 'pending',
                'reference' => $reference,
                'payment_method' => $payment_method
            ]);
    
            $description['restaurant_id'] = $restaurant_id;
            $description['reference'] = $reference;
            $description['shipping_fee'] = 60;
            $description['status'] = 'pending';
            $description['address'] = $address;
            $description['payment_method'] = $payment_method;

            OrderDescription::forceCreate($description);
        }

        if($auth) {

            return Inertia::render('Success', [
                'auth'    => $auth,
                'options' => [
                    'source' => $source,
                    'orders' => $orders
                ]
            ]);
        }

        return redirect('/');
    }

    public function failedPage(Request $request)
    {
        $auth = Auth::user();
        $source = session()->get('source');
        $orderId = session()->get('orders');
        $orders = Order::whereIn('id', $orderId)->get();

        if($auth) {

            return Inertia::render('Failed', [
                'auth'    => $auth,
                'options' => [
                    'source' => $source,
                    'orders' => $orders
                ]
            ]);
        }

        return redirect('/');
    }
}

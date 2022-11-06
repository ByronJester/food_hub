<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function viewOrders(Request $request)
    {
        $auth = Auth::user();

        if($auth) {
            $orders = Order::where('user_id', $auth->id)->get();

            return Inertia::render('CustomerOrders', [
                'auth'    => $auth,
                'options' => [
                    'orders' => $orders->groupBy('food_hub')
                ]
            ]);
        }

        return redirect('/');
    }

    public function placeOrder(Request $request)
    {
        $auth = Auth::user();

        $product = Product::where('id', $request->product_id)->first();

        $order = Order::where('user_id', $auth->id)->where('product_id', $request->product_id)->first();

        if($order) {
            $quantity = $request->quantity + $order->quantity;

            $order->quantity = $quantity;
            $order->amount = $product->amount * $quantity;

            $order->save();
        } else {
            $data = $request->toArray();

            $data['user_id'] = $auth->id;
            $data['amount'] = $product->amount * $request->quantity;

            Order::create($data);
        }

        
        return response()->json(['status' => 200], 200);  
    }

    public function changeQuantity(Request $request)
    {   
        $auth = Auth::user();

        $order = Order::where('id', $request->order_id)->first();
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
        $rules = [
            'payment_method' => "required|string",
            'reference_number' => "required_if:payment_method,gcash|numeric",
            'address' => "required", 
            'orders' => "required"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        // Str::random(10);

        return response()->json(['status' => 200, 'request' => $request->toArray()], 200);  
    }
}

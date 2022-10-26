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

        $data = $request->toArray();

        $data['user_id'] = $auth->id;
        $data['amount'] = $product->amount * $request->quantity;

        Order::create($data);

        return response()->json(['status' => 200], 200);  
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function loginView()
    {    
        $auth = Auth::user();

        if($auth) {
            if($auth->role == 1 || ($auth->role == 2 && $auth->user_type == 'owner')) {
                return redirect('/users');
            }

            if($auth->role == 2 && $auth->user_type == 'staff') {
                return redirect('/carts');
            }

            if($auth->role == 3 && $auth->user_type == 'customer') {
                return redirect('/restaurants');
            }
        }

        return Inertia::render('Login', [
            'auth'    => $auth,
            'options' => [
                'restaurants' => Restaurant::where('is_active', true)->get()
            ]
        ]);
    }

    public function saveUser(Request $request)
    {
        $rules = [
            'name' => "required|string",
            'phone' => "required|numeric|unique:users,phone",
            'email' => "required|unique:users,email|email:rfc,dns", 
            'picture_id' => "required",
            'user_type' => "required",
            'role' => "required",
            'password' => "sometimes|required|min:8",
            'confirm_password' => "sometimes|required|same:password|min:8",
        ];

        if($request->role == 2) {
            $rules = [
                'name' => "required|string",
                'phone' => "required|numeric|unique:users,phone",
                'email' => "required|unique:users,email|email:rfc,dns", 
                'picture_id' => "required",
                'user_type' => "required",
                'role' => "required",
                'password' => "sometimes|required|min:8",
                'confirm_password' => "sometimes|required|same:password|min:8",
                'restaurant_name' => "required|string",
                'image' => "required",
                'banner' => "required",
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        $userData = $request->except(['confirm_password', 'restaurant_name', 'image', 'banner']);

        $userData['password'] = Hash::make($request->password);

        if($id = $request->picture_id) {
            
            $path = public_path().'/images/uploads';

            $filename = time() . '_' . Str::random(8);

            $extension = $id->getClientOriginalExtension();
            
            $uplaod = $id->move($path, $filename . '.' . $extension);

            $userData['picture_id'] = $filename . '.' . $extension;
        }
        
        $saveUser = User::create($userData);

        if($request->role == 2 && $saveUser) {
            $restaurantData = $request->only(['restaurant_name', 'image', 'banner']);

            if($image = $request->image) {
            
                $path = public_path().'/images/uploads';
    
                $filename = time() . '_' . Str::random(8);
    
                $extension = $image->getClientOriginalExtension();
                
                $uplaod = $image->move($path, $filename . '.' . $extension);
    
                $restaurantData['image'] = $filename . '.' . $extension;
            }

            if($banner = $request->banner) {
            
                $path = public_path().'/images/uploads';
    
                $filename = time() . '_' . Str::random(8);
    
                $extension = $banner->getClientOriginalExtension();
                
                $uplaod = $banner->move($path, $filename . '.' . $extension);
    
                $restaurantData['banner'] = $filename . '.' . $extension;
            }

            $restaurantData['user_id'] = $saveUser->id;

            $saveRestaurant = Restaurant::create($restaurantData);
        }
        
        return response()->json(['status' => 200], 200);  
    }

    public function loginAccount(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return redirect()->back()->with('message', 'No account found.');
        }

        if(!$user->is_active) {
            return redirect()->back()->with('message', 'Your account is not verified.');
        }

        if(Auth::attempt($data)) {
            $auth = Auth::user();

            if($auth) {
                return redirect('/');
            }

        } else {
            return redirect()->back()->with('message', 'Invalid Credentials.');
        }
    }

    public function viewUsers(Request $request)
    { 
        $auth = Auth::user();

        if($auth) {
            $users = User::orderBy('created_at', 'desc')->whereNotIn('id', [$auth->id]);

            return Inertia::render('Users', [
                'auth'    => $auth,
                'options' => [
                    'users' => $users->get()
                ]
            ]);
        }

        return redirect('/');
    }

    public function changeStatus(Request $request)
    {
        User::where('id', $request->id)->update([
            'is_active' => $request->is_active
        ]);

        $restaurant = Restaurant::where('user_id', $request->id)->first();

        if($restaurant) {
            $restaurant->is_active = $request->is_active;

            $restaurant->save();
        } 

        return redirect()->back();
    }

    public function logoutAccount()
    {
        Auth::logout();

        return redirect('/');;
    }
} 
 
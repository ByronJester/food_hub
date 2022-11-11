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
            if($auth->role == 1) {
                return redirect('/users');
            }

            if($auth->role == 2) {
                return redirect('/restaurants');
            }

            if($auth->role == 3 && $auth->user_type == 'customer') {
                return redirect('/customers');
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
            'address' => "required"
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
                'address' => "required"
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        $userData = $request->except(['confirm_password', 'restaurant_name', 'image', 'banner']);

        $userData['password'] = Hash::make($request->password);

        if($request->role == 2) {
            $userData['reference'] = Str::random(10);
        }

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

            if($auth->role == 2) {
                $users = $users->where('reference', $auth->reference)->where('user_type', 'staff');
            } else {
                $users = $users->where('user_type', '!=', 'staff');
            }

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

    public function viewProfile(Request $request)
    {
        $auth = Auth::user();
        
        if($auth) {
            return Inertia::render('Profile', [
                'auth'    => $auth,
                'options' => [
                ]
            ]);
        }

        return redirect('/');
    }

    public function editProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => "required|string",
            'phone' => "required|numeric|unique:users,phone," . $request->id,
            'email' => "required|email:rfc,dns|unique:users,email," . $request->id, 
            'password' => "sometimes|required|min:8",
            'address' => "required",
            'confirm_password' => "sometimes|required|same:password|min:8",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        $data = $request->toArray();

        if(!!$request->password) {
            $data = $request->except(['confirm_password']);

            $data['password'] = Hash::make($request->password);
        }
        
        $saveUser = User::where('id', $request->id)->update($data);
        
        return response()->json(['status' => 200], 200); 
    }

    public function createStaff(Request $request)
    {
        $auth = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => "required|string",
            'phone' => "required|numeric",
            'email' => "required|email:rfc,dns",
            'address' => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        $data = $request->toArray();

        $data['password'] = Hash::make($request->phone);
        $data['is_active'] = true;
        $data['role'] = 2;
        $data['user_type'] = 'staff';
        $data['reference'] = $auth->reference;
        
        $saveUser = User::create($data);
        
        return response()->json(['status' => 200], 200); 
    }
} 
 
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\AuditTrail;
use App\Models\Verification;
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

        $restaurants = Restaurant::where('is_active', true)->get();

        return Inertia::render('Login', [
            'auth'    => $auth,
            'options' => [
                'restaurants' => $restaurants,
                'banners' => $restaurants->pluck('banner')
            ]
        ]);
    }

    public function saveUser(Request $request)
    {
        $rules = [
            'name' => "required|string",
            'phone' => "required|numeric|unique:users,phone|digits:11",
            'username' => "required|unique:users,username|min:6|regex:/(^[A-Za-z0-9]+$)+/", 
            'picture_id' => "required",
            'user_type' => "required",
            'role' => "required",
            'password' => "sometimes|required|min:8",
            'confirm_password' => "sometimes|required|same:password|min:8",
            'address' => "required",
        ];

        if($request->role == 2) {
            $rules = [
                'name' => "required|string",
                'phone' => "required|numeric|unique:users,phone|digits:11",
                'username' => "required|unique:users,username|min:6|regex:/(^[A-Za-z0-9]+$)+/", 
                'picture_id' => "required",
                'user_type' => "required",
                'role' => "required",
                'password' => "sometimes|required|min:8",
                'confirm_password' => "sometimes|required|same:password|min:8",
                'restaurant_name' => "required|string",
                'image' => "required",
                'banner' => "required",
                'address' => "required",
                'permit' => "required",
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        if($request->isDone) {
            $otpRules = [
                'code' => 'required|exists:verifications,code'
            ];
    
            $otpValidator = Validator::make($request->all(), $otpRules);
    
            if ($otpValidator->fails()) {
                return response()->json(['errors' => $otpValidator->messages(), 'status' => 422], 200);
            }

            $userData = $request->except(['confirm_password', 'restaurant_name', 'image', 'banner']);

            $userData['password'] = Hash::make($request->password);

            if($request->role == 2) {
                $userData['reference'] = Str::random(10);
            }

            if($id = $request->picture_id) {
                $pictures = '';

                foreach($id as $picture) {
                    $path = public_path().'/images/uploads';

                    $filename = time() . '_' . Str::random(8);

                    $extension = $picture->getClientOriginalExtension();
                    
                    $uplaod = $picture->move($path, $filename . '.' . $extension);

                    $imageName = $filename . '.' . $extension;
                    
                    if($pictures == '') {
                        $pictures = $imageName;
                    } else {
                        $pictures = $pictures . ',' . $imageName;
                    }

                    
                }

                $userData['picture_id'] = json_encode($pictures);
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

                if($permit = $request->permit) {
                
                    $path = public_path().'/images/uploads';
        
                    $filename = time() . '_' . Str::random(8);
        
                    $extension = $permit->getClientOriginalExtension();
                    
                    $uplaod = $permit->move($path, $filename . '.' . $extension);
        
                    $restaurantData['permit'] = $filename . '.' . $extension;
                }

                $restaurantData['user_id'] = $saveUser->id;

                $saveRestaurant = Restaurant::create($restaurantData);
            }

            Verification::where('code', $request->code)->delete();
        }

        
        
        return response()->json(['status' => 200], 200);  
    }

    public function loginAccount(Request $request)
    {
        $data = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        $user = User::where('username', $request->username)->first();

        if(!$user) {
            // return redirect()->back()->with('message', 'No account found.');
            return response()->json(['status' => 422, 'message' => 'No account found.' ], 200);  
        }

        if(!$user->is_active) {
            // return redirect()->back()->with('message', 'Your account is not verified.');
            return response()->json(['status' => 422, 'message' => 'Your account is not verified.' ], 200); 
        }

        if (Auth::attempt($data)) {
            // $code = sprintf("%06d", mt_rand(1, 999999));
            $auth = Auth::user();

            AuditTrail::forceCreate([
                'user_id' => $auth->id,
                'description' => $auth->name . ' has logged in.'
            ]);

            // Verification::create([ 
            //     'code' => $code
            // ]);

            // $phone = $user->phone;
            // $message = 'Your verification code is' . ' ' . $code;

            // $this->sendSms($phone, $message);

            // return redirect()->back()->with('message', 'success');
            return response()->json(['status' => 200, 'message' => 'success' ], 200); 
        } else {
            // return redirect()->back()->with('message', 'Invalid Credentials.');
            return response()->json(['status' => 422, 'message' => 'Invalid Credentials.' ], 200); 
        }
    }

    public function otpVerify(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $otpRules = [
            'code' => 'required|exists:verifications,code'
        ];

        $otpValidator = Validator::make($request->all(), $otpRules);

        if ($otpValidator->fails()) {
            return response()->json(['errors' => $otpValidator->messages(), 'status' => 422], 200);
        }

        Auth::attempt($data);

        Verification::where('code', $request->code)->delete();

        return response()->json(['status' => 200], 200); 
        
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
        $user = User::where('id', $request->id)->first();

        $user->is_active = $request->is_active;

        if($request->is_active && !!$user->picture_id) {
            $user->picture_id = null;
        }

        $user->save();

        $restaurant = Restaurant::where('user_id', $request->id)->first();

        if($restaurant) {
            $restaurant->is_active = $request->is_active;

            if($request->is_active && !!$restaurant->permit) {
                $restaurant->permit = null;
            }

            $restaurant->save();
        } 

        return redirect()->back();
    }

    public function logoutAccount()
    {
        $auth = Auth::user();

        $create = AuditTrail::forceCreate([
            'user_id' => $auth->id,
            'description' => $auth->name . ' has logged out.'
        ]);


        if($create) {
            Auth::logout();
        
        }
        
        return redirect('/');
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
        $auth = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => "required|string",
            'phone' => "required|numeric|digits:11|unique:users,phone," . $request->id,
            'email' => "required|email:rfc,dns|unique:users,email," . $request->id, 
            'password' => "sometimes|required|min:8",
            'address' => "required",
            'confirm_password' => "sometimes|required|same:password|min:8",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }

        if($request->code == null) {
            $code = sprintf("%06d", mt_rand(1, 999999));

            Verification::create([ 
                'code' => $code
            ]);

            $message = 'Your verification code is' . ' ' . $code;

            $this->sendSms($auth->phone, $message);
            
            return response()->json(['status' => 200], 200); 
        } else {
            $otpRules = [
                'code' => 'required|exists:verifications,code'
            ];
    
            $otpValidator = Validator::make($request->all(), $otpRules);
    
            if ($otpValidator->fails()) {
                return response()->json(['errors' => $otpValidator->messages(), 'status' => 422], 200);
            }
    
            $data = $request->except(['code']);
    
            if(!!$request->password) {
                $data = $request->except(['confirm_password']);
    
                $data['password'] = Hash::make($request->password);
            }
            
            $saveUser = User::where('id', $request->id)->update($data);
    
            Verification::where('code', $request->code)->delete();
            
            return response()->json(['status' => 200], 200); 
        }

    }

    public function createStaff(Request $request)
    {
        $auth = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => "required|string",
            'phone' => "required|numeric",
            'username' => "required|unique:users,username|min:6|regex:/(^[A-Za-z0-9]+$)+/", 
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

    public function saveVerification(Request $request)
    {
        $code = sprintf("%06d", mt_rand(1, 999999));

        Verification::create([ 
            'code' => $code
        ]);

        $phone = $request->phone;
        $message = 'Your verification code is' . ' ' . $code;

        $this->sendSms($phone, $message);

        return response()->json(['status' => 200], 200); 
    }

    public function viewTrails(Request $request)
    {
        $auth = Auth::user();

        $trails = AuditTrail::orderBy('created_at', 'desc')->where('user_id', '!=', $auth->id)->get();
        
        if($auth) {
            return Inertia::render('AuditTrails', [
                'auth'    => $auth,
                'options' => [
                    'trails' => $trails
                ]
            ]);
        }

        return redirect('/');
    }

    public function getCron(Request $request) 
    {
        return response()->json(['status' => 200], 200); 
    }
} 
 
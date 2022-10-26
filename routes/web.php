<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\OrderController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| 
*/

Route::middleware(['cors'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('view.login');
    });
    
    
    Route::prefix('login')->group(function () { 
        Route::get('/', [UserController::class, 'loginView'])->name('view.login');
        
    });
    
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'viewUsers'])->name('view.users');
        Route::post('/login', [UserController::class, 'loginAccount']);
        Route::post('/logout', [UserController::class, 'logoutAccount']);
        Route::post('/deactivate-reactivate', [UserController::class, 'changeStatus']);
        Route::post('/create-account', [UserController::class, 'saveUser']);
    });

    Route::prefix('restaurants')->group(function () {
        Route::get('/', [RestaurantController::class, 'viewRestaurant'])->name('view.restaurant');
        Route::post('/create-product', [RestaurantController::class, 'createProduct']);
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', [RestaurantController::class, 'viewRestaurants'])->name('view.restaurants');
        Route::post('/place-order', [OrderController::class, 'placeOrder']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'viewOrders'])->name('view.orders');
    });
});



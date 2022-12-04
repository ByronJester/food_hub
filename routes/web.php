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
        Route::get('/profile', [UserController::class, 'viewProfile'])->name('view.profile');
        Route::post('/login', [UserController::class, 'loginAccount']); 
        Route::post('/logout', [UserController::class, 'logoutAccount']);
        Route::post('/deactivate-reactivate', [UserController::class, 'changeStatus']);
        Route::post('/create-account', [UserController::class, 'saveUser']);
        Route::post('/edit-profile', [UserController::class, 'editProfile']);
        Route::post('/create-staff', [UserController::class, 'createStaff']);
        Route::post('/save-verification', [UserController::class, 'saveVerification']);
        Route::post('/verify-otp', [UserController::class, 'otpVerify']);
    });

    Route::prefix('restaurants')->group(function () {
        Route::get('/', [RestaurantController::class, 'viewRestaurant'])->name('view.restaurant');
        Route::get('/my-trays', [RestaurantController::class, 'viewTrays'])->name('view.trays');
        Route::post('/create-product', [RestaurantController::class, 'createProduct']);
        Route::post('/create-address', [RestaurantController::class, 'createAddress']);
        Route::post('/remove-address', [RestaurantController::class, 'removeAddress']);
        Route::post('/change-image', [RestaurantController::class, 'changeImage']);
        Route::post('/product/deactivate-reactivate', [RestaurantController::class, 'reactivateDeactivateProduct']);
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', [RestaurantController::class, 'viewRestaurants'])->name('view.restaurants');
        Route::post('/place-order', [OrderController::class, 'placeOrder']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'viewOrders'])->name('view.orders');
        Route::post('/change-quantity', [OrderController::class, 'changeQuantity']);
        Route::post('/remove-order', [OrderController::class, 'removeOrder']);
        Route::post('/checkout-order', [OrderController::class, 'checkoutOrder']);
        Route::post('/active-tab', [OrderController::class, 'changeActiveTab']);
        Route::post('/change-status', [OrderController::class, 'changeStatus']);
        Route::post('/get-address', [OrderController::class, 'getAddress']);
        Route::post('/buy-now', [OrderController::class, 'buyNow']);
        Route::post('/order-received', [OrderController::class, 'orderReceived']);
    });

    Route::prefix('trails')->group(function () {
        Route::get('/', [UserController::class, 'viewTrails'])->name('view.trails');
    });

    Route::prefix('paymongo')->group(function () {
        Route::get('/success', [OrderController::class, 'successPage']);
        Route::get('/failed', [OrderController::class, 'failedPage']);
    });

    Route::prefix('cron')->group(function () {
        Route::get('/', [UserController::class, 'getCron']);
    });
});



<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



 Route::get('orders',[ApiController::class, 'orders']);
 Route::post('orderDetails',[ApiController::class, 'orderDetails']);
 Route::post('orderPartyName',[ApiController::class, 'orderPartyName']);
 Route::post('bilty_image',[ApiController::class, 'bilty_image']);
 Route::post('login',[ApiController::class, 'login']);

<?php

use App\Http\Controllers\BulkImagesController;
use Marvel\Database\Models\Profile;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Marvel\Database\Models\Order;

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

Route::get('/bulk_images',[BulkImagesController::class,'bulk_images']);
Route::post('/bulk_images',[BulkImagesController::class,'store_bulk_images']);

Route::get('/update_tracking_order_id/{order_id}/{tracking_id}',function(Request $request, $order_id, $tracking_id){
    $order = Order::find($order_id);
    $order->romario_tracking_id = $tracking_id;
    $order->save();
    
});

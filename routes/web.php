<?php

use Illuminate\Http\Request;
use Marvel\Database\Models\Profile;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/add-contact', function (Request $request) {
    $phone_number = $request->phone_number;

    if (empty($phone_number)) {
        return ['message' => config('shop.app_notice_domain') . 'ERROR.EMPTY_MOBILE_NUMBER', 'success' => false];
    }
    $profile = Profile::where('contact', $phone_number)->first();

    return [
        'message' => 'Success',
        'success' => true,
        // 'provider' => config('auth.active_otp_gateway'),
        // 'id' => $sendOtpCode->getId(),
        'phone_number' => $phone_number,
        'is_contact_exist' => $profile ? true : false
    ];
});

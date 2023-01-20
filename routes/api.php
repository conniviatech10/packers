<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//live routes
Route::prefix('v1')->namespace('App\Http\Controllers\Api')->group(function(){

    Route::post('/login', 'Auth\LoginController@login')->name('api.login');
    Route::post('/otp/generate', 'Auth\LoginController@generate_otp')->name('api.otp.generate');
    Route::post('/otp/validate', 'Auth\LoginController@validate_otp')->name('api.otp.validate');
    Route::post('/otp_login', 'Auth\LoginController@login')->name('api.otp_login');
    Route::post('/register', 'Auth\RegisterController@register')->name('api.register');
    Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('api.password.reset');
    Route::post('/password/verify', 'Auth\ResetPasswordController@verify')->name('api.password.verify');
    
    //authorized Api
    Route::middleware('auth:api')->group(function(){
        Route::post('profile/image', 'Account\UserController@imageUploader');
        Route::resource('profile',Account\UserController::class);
        Route::resource('request',Account\OrderController::class); 
        Route::get('appointment/{id}', 'Account\OrderController@appointment');
        Route::resource('quotation',Account\QuotationController::class);
        //media user profile upload
        //services
        Route::resource('service',ServiceController::class)->only(['store']);
        
    });

    Route::prefix('localisation')->group(function(){
        Route::resource('country',Localisation\CountryController::class)->only('index');
        Route::resource('state',Localisation\StateController::class)->only('index');       
        Route::resource('property-type',Localisation\PropertyTypeController::class)->only(['index','show']);
        Route::resource('shifting-type',Localisation\ShiftingTypeController::class)->only(['index','show']);
        Route::resource('service',Localisation\ServiceController::class)->only(['index']);
    });

    //
    Route::resource('category',CategoryController::class)->only(['index','show']);
    Route::resource('items',ItemsController::class)->only(['index','show']);
    //Partnership request
    Route::resource('partner-request',PartnerRequestController::class)->only(['store']);
    //static pages
    Route::resource('pages',PageController::class)->only(['index','show']);
    //banner
    Route::resource('banner',BannerController::class)->only(['index']);

});
<?php

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
//Route::get('secure-login',function(){ \Auth::loginUsingId(40, true); });
Route::get('/', function () {
    return view('welcome');    
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class,'logout']);

//admin routes
Route::prefix('admin')->middleware('auth')->group(function(){
   Route::get('/',[App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin');
   Route::get('/dashboard',[App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
   Route::get('/profile', [App\Http\Controllers\Admin\UserController::class,'profile']);
   Route::post('/profile', [App\Http\Controllers\Admin\UserController::class,'profile_update']);
   Route::get('/change-password', [App\Http\Controllers\Admin\UserController::class,'change_password']);
   Route::post('/change-password', [App\Http\Controllers\Admin\UserController::class,'update_password']);
   //only those have manage_user permission will get access
   //Route::group(['middleware' => 'can:manage_user'], function(){
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class,'index']);
        Route::get('/user/get-list', [App\Http\Controllers\Admin\UserController::class,'getUserList']);
        Route::get('/user/create', [App\Http\Controllers\Admin\UserController::class,'create']);
        Route::post('/user/create', [App\Http\Controllers\Admin\UserController::class,'store'])->name('create-user');
        Route::get('/user/{id}', [App\Http\Controllers\Admin\UserController::class,'edit']);
        Route::post('/user/update', [App\Http\Controllers\Admin\UserController::class,'update']);
        Route::get('/user/delete/{id}', [App\Http\Controllers\Admin\UserController::class,'delete']);
   //});
   //only those have manage_role permission will get access
   Route::group(['middleware' => 'can:manage_role|manage_user'], function(){
		Route::get('/roles', [App\Http\Controllers\Admin\RolesController::class,'index']);
		Route::get('/role/get-list', [App\Http\Controllers\Admin\RolesController::class,'getRoleList']);
		Route::post('/role/create', [App\Http\Controllers\Admin\RolesController::class,'create']);
		Route::get('/role/edit/{id}', [App\Http\Controllers\Admin\RolesController::class,'edit']);
		Route::post('/role/update', [App\Http\Controllers\Admin\RolesController::class,'update']);
		Route::get('/role/delete/{id}', [App\Http\Controllers\Admin\RolesController::class,'delete']);
	});
    //only those have manage_permission permission will get access
	Route::group(['middleware' => 'can:manage_permission|manage_user'], function(){
		Route::get('/permission', [App\Http\Controllers\Admin\PermissionController::class,'index']);
		Route::get('/permission/get-list', [App\Http\Controllers\Admin\PermissionController::class,'getPermissionList']);
		Route::post('/permission/create', [App\Http\Controllers\Admin\PermissionController::class,'create']);
		Route::get('/permission/update', [App\Http\Controllers\Admin\PermissionController::class,'update']);
		Route::get('/permission/delete/{id}', [App\Http\Controllers\Admin\PermissionController::class,'delete']);
	});

	// get permissions
	Route::get('get-role-permissions-badge', [App\Http\Controllers\Admin\PermissionController::class,'getPermissionBadgeByRole']);

    // Order Request /Lead
    Route::resource('/request', App\Http\Controllers\Admin\OrderController::class)->names('leads');   
   
    // Payment History 
    Route::resource('/payment', App\Http\Controllers\Admin\PaymentController::class)->names('payment.index');   

    //appointment for lead
    Route::post('/request-appointment', [\App\Http\Controllers\Admin\OrderController::class,'storeAppointment']);
    
    Route::post('/clear-cache', [App\Http\Controllers\Admin\Setting\CacheController::class,'index']);
    Route::resource('quotation',App\Http\Controllers\Admin\QuotationController::class)->names('quotation');
    Route::resource('/category/item', App\Http\Controllers\Admin\CategoryItemController::class)->names('category.item');  
    Route::resource('/category', App\Http\Controllers\Admin\CategoryController::class)->names('category');  
    //pages
    Route::resource('/page', App\Http\Controllers\Admin\PageController::class)->names('admin.page');
    //Partner Request 
    Route::resource('/partner-request', App\Http\Controllers\Admin\PartnerRequestController::class)->names('admin.partner.request');
    //service request
    Route::resource('/service', App\Http\Controllers\Admin\ServiceController::class)->names('admin.service');
    
    Route::resource('/banner', App\Http\Controllers\Admin\BannerController::class)->names('admin.banner');
    //settings
    Route::prefix('setting')->middleware(['can:manage_setting'])->group(function(){
        Route::get('/clear-cache', [App\Http\Controllers\Admin\Setting\CacheController::class,'index']);
        Route::resource('/country', App\Http\Controllers\Admin\Setting\CountryController::class)->names('country');  
        Route::resource('/state', App\Http\Controllers\Admin\Setting\StateController::class)->names('state');  
        Route::resource('/request-status', App\Http\Controllers\Admin\Setting\OrderStatusController::class)->names('order.status');
        Route::resource('/shifting-type', App\Http\Controllers\Admin\Setting\ShiftingTypeController::class)->names('shifting_type');
    });
    
}); 
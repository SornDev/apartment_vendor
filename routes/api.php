<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\UserController;
// use App\Http\Controllers\StoreController;
// use App\Http\Controllers\TransectionController;

use App\Http\Controllers\API\DocCatController;
use App\Http\Controllers\API\DocController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\DocWorkController;
use App\Http\Controllers\API\ReceiptController;
use App\Http\Controllers\API\TransectionController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\API\AccTypeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);
Route::get('logout',[UserController::class,'logout']);

Route::controller(StoreController::class)->group(function (){
    Route::get('store','index');
    Route::get('store/edit/{id}','edit');
    Route::post('store/add','add');
    Route::post('store/update/{id}','update');
    Route::delete('store/delete/{id}','delete');
});

// Route::controller(StoreController::class)->group(function (){
    // Route::get('TransectionController','index');
    // Route::get('TransectionController/edit/{id}','edit');
    // Route::post('TransectionController/add','add');
    // Route::post('TransectionController/update/{id}','update');
    // Route::delete('TransectionController/delete/{id}','delete');
// });


// --------

Route::controller(DocCatController::class)->group(function (){
    Route::get('doccat','index');
    Route::get('doccat/edit/{id}','edit');
    Route::post('doccat/add','add');
    Route::post('doccat/update/{id}','update');
    Route::delete('doccat/delete/{id}','delete');
});


Route::controller(DocController::class)->group(function (){
    Route::get('doc','index');
    Route::get('doc/get/{id}','getdoc');
    Route::get('doc/edit/{id}','edit');
    Route::post('doc/add','add');
    Route::post('doc/update/{id}','update');
    Route::delete('doc/delete/{id}','delete');
});

Route::controller(UserController::class)->group(function (){
    Route::get('user/get','index');
    Route::get('user/edit/{id}','edit');
    Route::post('user/add','add');
    Route::post('user/update/{id}','update');
    Route::delete('user/delete/{id}','delete');
});

Route::controller(CustomerController::class)->group(function (){
    Route::get('cus/get','index');
    Route::get('cus/search','search');
    Route::get('cus/edit/{id}','edit');
    Route::post('cus/add','add');
    Route::post('cus/update/{id}','update');
    Route::delete('cus/delete/{id}','delete');
});

Route::controller(DocWorkController::class)->group(function (){
    Route::get('dwork/get','index');
    Route::get('dwork/edit/{id}','edit');
    Route::post('dwork/add','add');
    Route::post('dwork/update/{id}','update');
    Route::delete('dwork/delete/{id}','delete');
});

Route::controller(ReceiptController::class)->group(function (){
    Route::get('rec/get','index');
    Route::get('rec/edit/{id}','edit');
    Route::post('rec/add','add');
    Route::post('rec/update/{id}','update');
    Route::delete('rec/delete/{id}','delete');
    Route::get('rec/print/80mm/{id}','print80');
    Route::get('rec/print/a4/{id}','printa4');
    Route::get('rec/print/quo/{id}','printquo');
});

Route::controller(TransectionController::class)->group(function (){
    Route::get('acc/get','index');
    Route::get('acc/edit/{id}','edit');
    Route::post('acc/add','add');
    Route::post('acc/update/{id}','update');
    Route::delete('acc/delete/{id}','delete');
    Route::get('gettran/dashtran','dashtran');
    Route::get('gettran/gmn','gmn');
    Route::get('gettran/dashtop','dashtop');
    Route::get('gettran/incexp','incexp');
});

Route::controller(SettingController::class)->group(function (){
    Route::get('setting/get','index');
    Route::post('setting/update','update');
});


Route::controller(ReportController::class)->group(function (){
    Route::post('report/finance','finance');
});

Route::controller(RolesController::class)->group(function (){
    Route::get('roles/get','index');
    Route::get('roles/edit/{id}','edit');
    Route::post('roles/add','add');
    Route::post('roles/update/{id}','update');
    Route::delete('roles/delete/{id}','delete');
});

Route::controller(AccTypeController::class)->group(function (){
    Route::get('acc_type/get','index');
    Route::get('acc_type/edit/{id}','edit');
    Route::post('acc_type/add','add');
    Route::post('acc_type/update/{id}','update');
    Route::delete('acc_type/delete/{id}','delete');
});
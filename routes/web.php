<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GraphController;

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

Route::middleware(['guest'])->group(function(){
    Route::get('/',[CompanyInfoController::class, 'show'])->name('heading');
});
Route::post('/settings/store/',[CompanyInfoController::class, 'store'])->name('settings_store');
Route::get('/settings/create/',[CompanyInfoController::class, 'create'])->name('settings_create');


Route::middleware(['auth'])->group(function(){

    Route::middleware(['admin'])->group(function(){

        Route::get('/settings',[CompanyInfoController::class, 'edit'])->name('settings')->middleware('password.confirm');
        Route::get('/settings/update/')->name('settings_update');
        Route::post('/settings/update/{company_info}',[CompanyInfoController::class, 'update']);

        Route::group(['prefix' => 'tax'], function () {
            Route::match(['get','post'],'/',[TaxController::class, 'index'])->name('tax');
            Route::post('/{tax}/edit',[TaxController::class, 'edit']);
            Route::post('/create',[TaxController::class, 'store']);
            Route::post('/{tax}/update',[TaxController::class, 'update']);
            Route::delete('/{tax}/delete',[TaxController::class, 'destroy']);
        });

        Route::group(['prefix' => 'unit'], function () {
            Route::match(['get','post'],'/',[UnitController::class, 'index'])->name('unit');
            Route::post('/{unit}/edit',[UnitController::class, 'edit']);
            Route::post('/create',[UnitController::class, 'store']);
            Route::post('/{unit}/update',[UnitController::class, 'update']);
            Route::delete('/{unit}/delete',[UnitController::class, 'destroy']);
        });

        Route::group(['prefix' => 'report'], function () {
            Route::match(['get','post'],'/',[ReportController::class, 'index'])->name('report');
            Route::match(['get','post'],'/{document}-{table}/{id}',[ReportController::class,'create']);
            Route::match(['get','post'],'/{from_date}/{to_date}/{table}',[ReportController::class,'show']);

        });

        Route::match(['get','post'],'/graph',[GraphController::class, 'index'])->name('graph');
        Route::post('/graph/edit',[GraphController::class, 'edit']);

        Route::match(['get','post'],'/brand',[BrandController::class, 'index'])->name('brand');
        Route::post('/brand/{brand}/edit',[BrandController::class, 'edit']);
        Route::post('/brand/create',[BrandController::class, 'store']);
        Route::post('/brand/{brand}/update',[BrandController::class, 'update']);
        Route::delete('/brand/{brand}/delete',[BrandController::class, 'destroy']);

        Route::match(['get','post'],'/category',[CategoryController::class, 'index'])->name('category');
        Route::post('/category/{category}/edit',[CategoryController::class, 'edit']);
        Route::post('/category/create',[CategoryController::class, 'store']);
        Route::post('/category/{category}/update',[CategoryController::class, 'update']);
        Route::delete('/category/{category}/delete',[CategoryController::class, 'destroy']);


        Route::match(['get','post'],'/user',[UserController::class, 'index'])->name('user');
        Route::post('/user/create',[UserController::class, 'store']);
        Route::get('/user/create',[UserController::class, 'store']);
        Route::post('/user/{user}/edit',[UserController::class, 'edit']);
        Route::delete('/user/{user}/delete',[UserController::class, 'destroy']);
        Route::match(['get','post','put'],'/user/{user}/update', [UserController::class, 'update']);

        Route::match(['get','post'],'/supplier',[SupplierController::class, 'index'])->name('supplier');
        Route::post('/supplier/create',[SupplierController::class, 'store']);
        Route::get('/supplier/create',[SupplierController::class, 'store']);
        Route::post('/supplier/{supplier}/edit',[SupplierController::class, 'edit']);
        Route::delete('/supplier/{supplier}/delete',[SupplierController::class, 'destroy']);
        Route::put('/supplier/{supplier}/update', [SupplierController::class, 'update']);
        Route::post('/supplier/{supplier}/update', [SupplierController::class, 'update']);

        Route::match(['get','post'],'/product',[ProductController::class, 'index'])->name('product');
        Route::post('/product/{product}/edit',[ProductController::class, 'edit']);
        Route::post('/product/create',[ProductController::class, 'store']);
        Route::post('/product/list',[ProductController::class, 'create']);
        Route::post('/product/{product}/show',[ProductController::class, 'show']);
        Route::post('/product/{product}/update',[ProductController::class, 'update']);
        Route::delete('/product/{product}/delete',[ProductController::class, 'destroy']);

        Route::delete('/purchase/{purchase}/delete',[PurchaseController::class, 'destroy']);
        Route::delete('/sales/{sales}/delete',[SaleController::class, 'destroy']);
    });
    Route::get('/dashboard',[CompanyInfoController::class, 'index'])->name('dashboard');

    Route::post('/profile/{user}/update',[UserController::class, 'update']);
    Route::get('/profile',[UserController::class, 'create'])->name('profile');

    Route::match(['get','post'],'/sales',[SaleController::class, 'index'])->name('sales');
    Route::post('/sales/list',[SaleController::class, 'show'])->name('sales_status');
    Route::post('/sales/{sales}/edit',[SaleController::class, 'edit']);
    Route::post('/sales/max',[SaleController::class, 'create']);
    Route::post('/sales/create',[SaleController::class, 'store']);
    Route::post('/sales/{sales}/update',[SaleController::class, 'update']);

    Route::match(['get','post'],'/purchase',[PurchaseController::class, 'index'])->name('purchase');
    Route::post('/purchase/list',[PurchaseController::class, 'show']);
    Route::post('/purchase/{purchase}/edit',[PurchaseController::class, 'edit']);
    Route::post('/purchase/create',[PurchaseController::class, 'store']);
    Route::post('/purchase/{purchase}/update',[PurchaseController::class, 'update']);
});

require __DIR__.'/auth.php';

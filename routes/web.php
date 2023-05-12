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
use App\Http\Controllers\DashboardController;

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
    Route::get('/',[CompanyInfoController::class, 'show'])->name('welcome');
});
Route::post('/settings/store/',[CompanyInfoController::class, 'store'])->name('settings_store');
Route::get('/settings/create/',[CompanyInfoController::class, 'create'])->name('settings_create');


Route::middleware(['auth','verified'])->group(function(){

    Route::middleware(['admin'])->group(function(){

        Route::get('/settings',[CompanyInfoController::class, 'edit'])->name('settings')->middleware('password.confirm');
        Route::match(['put','patch'],'/settings/update/{company_info}',[CompanyInfoController::class, 'update'])->name('settings_update');

        Route::group(['prefix' => 'tax'], function () {
            Route::get('/',[TaxController::class, 'index'])->name('tax');
            Route::get('/{tax}/edit',[TaxController::class, 'edit']);
            Route::post('/store',[TaxController::class, 'store']);
            Route::match(['put','patch'],'/{tax}/update',[TaxController::class, 'update']);
            Route::delete('/{tax}/delete',[TaxController::class, 'destroy']);
        });

        Route::group(['prefix' => 'unit'], function () {
            Route::get('/',[UnitController::class, 'index'])->name('unit');
            Route::get('/{unit}/edit',[UnitController::class, 'edit']);
            Route::post('/store',[UnitController::class, 'store']);
            Route::match(['put','patch'],'/{unit}/update',[UnitController::class, 'update']);
            Route::delete('/{unit}/delete',[UnitController::class, 'destroy']);
        });

        Route::group(['prefix' => 'report'], function () {
            Route::match(['get','post'],'/',[ReportController::class, 'index'])->name('report');
            Route::match(['get','post'],'/{table}/csv/{from_date}/{to_date}',[ReportController::class,'downloadCSV'])->name('report.csv');
            Route::match(['get','post'],'/{document}-{table}/{id}',[ReportController::class,'create'])->name('report.document');
            Route::match(['get','post'],'/{from_date}/{to_date}/{table}',[ReportController::class,'show'])->name('report.order');

        });

        Route::match(['get','post'],'/graph',[GraphController::class, 'index'])->name('graph');
        Route::post('/graph/edit',[GraphController::class, 'edit']);

        Route::get('/brand',[BrandController::class, 'index'])->name('brand');
        Route::get('/brand/{brand}/edit',[BrandController::class, 'edit']);
        Route::post('/brand/store',[BrandController::class, 'store']);
        Route::match(['put','patch'],'/brand/{brand}/update',[BrandController::class, 'update']);
        Route::delete('/brand/{brand}/delete',[BrandController::class, 'destroy']);

        Route::match(['get','post'],'/category',[CategoryController::class, 'index'])->name('category');
        Route::get('/category/{category}/edit',[CategoryController::class, 'edit']);
        Route::post('/category/store',[CategoryController::class, 'store'])->name('category.store');
        Route::match(['put','patch'],'/category/{category}/update',[CategoryController::class, 'update'])->name('category.update');
        Route::delete('/category/{category}/delete',[CategoryController::class, 'destroy']);


        Route::get('/user',[UserController::class, 'index'])->name('user');
        Route::post('/user/store',[UserController::class, 'store']);
        Route::post('/user/{user}/reset',[UserController::class, 'password_reset'])->name('admin.passwort.reset');
        Route::get('/user/{user}/edit',[UserController::class, 'edit']);
        Route::delete('/user/{user}/delete',[UserController::class, 'destroy']);
        Route::match(['put','patch'],'/user/{user}/update', [UserController::class, 'update']);

        Route::get('/supplier',[SupplierController::class, 'index'])->name('supplier');
        Route::post('/supplier/store',[SupplierController::class, 'store']);
        Route::get('/supplier/{supplier}/edit',[SupplierController::class, 'edit']);
        Route::delete('/supplier/{supplier}/delete',[SupplierController::class, 'destroy']);
        Route::match(['put','patch'],'/supplier/{supplier}/update', [SupplierController::class, 'update']);


        Route::get('/product',[ProductController::class, 'index'])->name('product');
        Route::get('/product/{product}/edit',[ProductController::class, 'edit']);
        Route::post('/product/store',[ProductController::class, 'store']);
        Route::get('/product/list',[ProductController::class, 'create']);
        Route::get('/product/{productId}/show',[ProductController::class, 'show']);
        Route::match(['put','patch'],'/product/{product}/update',[ProductController::class, 'update']);
        Route::delete('/product/{product}/delete',[ProductController::class, 'destroy']);

        Route::delete('/purchase/{purchase}/delete',[PurchaseController::class, 'destroy']);
        Route::delete('/sales/{sales}/delete',[SaleController::class, 'destroy']);
    });
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');

    Route::match(['put','patch'],'/profile/{user}/update',[UserController::class, 'update'])->name('profile.update');
    Route::get('/profile',[UserController::class, 'create'])->name('profile');

    Route::get('/sales',[SaleController::class, 'index'])->name('sales');
    Route::get('/sales/list',[SaleController::class, 'show'])->name('sales_status');
    Route::get('/sales/{sales}/edit',[SaleController::class, 'edit']);
    Route::get('/sales/max',[SaleController::class, 'create']);
    Route::post('/sales/csv/upload',[SaleController::class, 'uploadCSV'])->name('sales.upload');
    Route::post('/sales/store',[SaleController::class, 'store']);
    Route::match(['put','patch'],'/sales/{sales}/update',[SaleController::class, 'update']);

    Route::get('/purchase',[PurchaseController::class, 'index'])->name('purchase');
    Route::post('/purchase/csv/upload',[PurchaseController::class, 'uploadCSV'])->name('purchase.upload');
    Route::get('/purchase/list',[PurchaseController::class, 'show']);
    Route::get('/purchase/{purchase}/edit',[PurchaseController::class, 'edit']);
    Route::post('/purchase/store',[PurchaseController::class, 'store']);
    Route::match(['put','patch'],'/purchase/{purchase}/update',[PurchaseController::class, 'update']);
});

require __DIR__.'/auth.php';

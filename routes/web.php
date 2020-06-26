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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {

    //Route yang berada dalam group ini hanya dapat diakses oleh user
    //yang memiliki role admin
    Route::group(['middleware' => ['role:admin']], function () {
        Route::resource('users', 'UserController')->except([
            'create', 'show', 'edit'
        ]);
        Route::get('/users/roles/{id}', 'UserController@roles')->name('users.roles');
        Route::put('/users/roles/{id}', 'UserController@setRole')->name('users.set_role');
        Route::post('/users/permission', 'UserController@addPermission')->name('users.add_permission');
        Route::get('/users/role-permission', 'UserController@rolePermission')->name('users.roles_permission');
        Route::put('/users/permission/{role}', 'UserController@setRolePermission')->name('users.setRolePermission');
    
        Route::resource('/role', 'RoleController')->except([
            'create', 'show', 'edit', 'update'
        ]);
    });

    //route yang berada dalam group ini, hanya bisa diakses oleh user
    //yang memiliki permission yang telah disebutkan dibawah
    Route::group(['middleware' => ['permission:users|products|categories']], function() {
        Route::resource('categories', 'CategoryController')->except([
            'create', 'show'
        ]);
        Route::resource('products', 'ProductController');
    });

    //route group untuk kasir dan admin
    Route::group(['middleware' => ['role:admin|kasir']], function() {
        Route::get('/transaction', 'OrderController@addOrder')->name('orders.transaction');
        Route::get('/checkout', 'OrderController@checkout')->name('orders.checkout');
        Route::post('/checkout', 'OrderController@storeOrder')->name('order.storeOrder');

        Route::get('/orders', 'OrderController@index')->name('orders.index');
        Route::get('/order/pdf/{invoice}', 'OrderController@invoicePdf')->name('order.pdf');
        Route::get('/order/excel/{invoice}', 'OrderController@invoiceExcel')->name('order.excel');
    });

    Route::get('/home', 'HomeController@index')->name('home');
});
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

Route::get('/', 'WebController@index');
Route::get('/contact_us', 'WebController@contactUs');
// RESTful API


Route::group(['middleware' => 'check.dirty'], function ()
{
    Route::resource('products', 'ProductController');

});

// notification
Route::post('admin/orders/{id}/delivery', 'OrderController@delivery');
Route::post('admin/tools/update-product-price', 'ToolController@updateProductPrice');
Route::post('admin/tools/create-product-redis', 'ToolController@createProductRedis');

// 增加建立會員的路由
Route::post('signup', 'AuthController@signup');

Route::post('login', 'AuthController@login');

                            // 使用 auth 提供的 middleware
Route::group(['middleware' => 'auth:api'],function () {
    Route::get('user', 'AuthController@user');
    Route::get('logout', 'AuthController@logout');
    // 這兩個要放在 auth:api 內
    Route::resource('carts', 'CartController');
    Route::resource('cart-items', 'CartItemController');
    // 加上購物車的路由
    Route::post('carts/checkout', 'CartController@checkout');

});

Route::get('products/{id}/shared-url', 'ProductController@sharedUrl');

// exl 下載路由
Route::get('admin/orders/excel/export', 'OrderController@export');
// Route::group([
//     // middleware 中間層   ↓ 自己寫的 function
//     'middleware' => ['checkValueIp'],
//     // 在網址前面都加上 web 的前綴用詞
//     'prefix' => 'web',
//     // 要在 Http 內 controller 增加一個 Web的資料夾才能用 
//     'namespace' => 'Web',
// ],function (){
//     Route::get('/index', 'HomeController@index');
//     Route::post('/print', 'HomeController@index');
// });

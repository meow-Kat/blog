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
    return view('welcome');
});
// RESTful API
Route::resource('products', 'ProductController');

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

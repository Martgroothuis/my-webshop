<?php

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


Auth::routes();

Route::get('/', 'ProductController@index');

Route::resource('/products', 'ProductController');


Route::get('/home', 'HomeController@index')->name('home');




Route::get('/admin', 'AdminController@index')->middleware('is_admin')->name('admin');




Route::get('/customers', 'AdminController@customers')->middleware('is_admin')->name('admin');

Route::get('/image', 'ImageController@index')->middleware('is_admin')->name('admin');

Route::post('/uploadimages', 'ImageController@UploadImage');



Route::get('/shoppingcart', 'ShoppingCartController@show')->name('shoppingcart');

Route::resource('/api/shoppingcart', 'Api\ApiShoppingCartController');

Route::resource('/api/orders', 'Api\ApiOrderController');


Route::resource('/payments', 'PaymentController')->middleware('auth');

Route::resource('/orders', 'OrderController')->middleware('auth');






// Route::get('/images/small/{imagename}', 'ImageController@SmallImage');

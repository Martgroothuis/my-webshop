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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/products/{product}', 'ProductController@showProduct');




Route::get('/admin', 'AdminController@index')->middleware('is_admin')->name('admin');




Route::get('/customers', 'AdminController@customers')->middleware('is_admin')->name('admin');

Route::get('/image', 'ImageController@index')->middleware('is_admin')->name('admin');

Route::post('/uploadimages', 'ImageController@UploadImage');



Route::get('/shoppingcard', 'ShoppingcardController@show');

Route::resource('/api/shoppingcard', 'ShoppingCardController');



// Route::get('/images/small/{imagename}', 'ImageController@SmallImage');

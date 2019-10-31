<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::resource('/productversions', 'ProductVersionController');

Route::resource('/products', 'ProductController');

Route::resource('/images', 'ImageController');

Route::get('/sizes/{product}', 'SizeController@show');


// Route::group(['middleware' => ['web']], function () {
//     Route::resource('/shoppingcard', 'ShoppingCardController');
// });

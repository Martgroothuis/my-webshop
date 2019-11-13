<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// products data
Route::resource('/products', 'Api\ApiProductController');
Route::resource('/productversions', 'Api\ApiProductVersionController');

// admin data
// Route::group(['middleware' => ['is_admin']], function () {
Route::resource('/colors', 'Api\ApiColorController');
Route::resource('/lengthsizes', 'Api\ApiLengthSizeController');
Route::resource('/waistsizes', 'Api\ApiWaistSizeController');
Route::resource('/statuses', 'Api\ApiStatusController');
// });

Route::resource('/sizes', 'Api\ApiSizeController');




// slecht
Route::resource('/images', 'ImageController');



Route::get('/sizes/{product}', 'SizeController@show');


Route::group(['middleware' => ['web']], function () {
    // Route::resource('/orders', 'Api\ApiOrderController');
});

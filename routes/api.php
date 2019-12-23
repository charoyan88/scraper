<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::prefix('market')->group(function () {
        Route::get('/', 'MarketController@all');
    });
    Route::prefix('find')->group(function () {
        Route::get('products', 'ProductController@getProducts');
    });
    Route::post('products', 'ProductController@store');
    Route::get('products', 'ProductController@all');
});

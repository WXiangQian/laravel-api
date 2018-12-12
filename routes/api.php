<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::options("/{any}", function () {
    return 'ok';
})->where('any', '.*');

Route::group(['prefix' => '/v1', 'namespace' => 'V1'], function () {
    Route::group(['middleware' => 'api.auth'], function () {
        Route::get('news', 'IndexController@news');

    });
    Route::get('home', 'IndexController@index');
    Route::post('login', 'IndexController@login');
    Route::post('register', 'IndexController@register');
});


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
    Route::get('/home', 'IndexController@index');
    // login register
    Route::post('/login', 'IndexController@login');
    Route::post('/register', 'IndexController@register');
    // news
    Route::group(['prefix' => '/news'], function () {
        Route::get('/list', 'ArticleController@getNewsList');
    });
    // amap
    Route::post('/amap/regeo', 'AmapController@getPosition');
    Route::get('/ip', 'AmapController@getIp');
    // sina
    Route::post('/sina/short_url', 'ShortUrlController@getShortUrl');
});


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

    // index
    Route::get('/home', 'IndexController@index');
    Route::post('/express', 'IndexController@express');

    // login register
    Route::post('/login', 'UserController@login');
    Route::post('/register', 'UserController@register');

    // hashids
    Route::post('/hash_ids/encode', 'HashIdsController@hashIdsEncode');

    //user
    Route::group(['prefix' => '/user'], function () {
        Route::group(['middleware' => 'api.auth'], function () {
            Route::get('/vote', 'UserController@getVoteArticleByUserId');
        });
    });

    // news
    Route::group(['prefix' => '/article'], function () {
        Route::get('/list', 'ArticleController@getArticleList');
        Route::get('/info', 'ArticleController@getArticleInfo');
        Route::group(['middleware' => 'api.auth'], function () {
            Route::get('/info/vote', 'ArticleController@voteArticle');
        });
    });

    // amap
    Route::post('/amap/geo', 'AmapController@getLonAndLat');
    Route::post('/amap/regeo', 'AmapController@getPosition');
    Route::get('/location', 'AmapController@getLocation');

    // sina
    Route::post('/sina/short_url', 'ShortUrlController@getShortUrl');
});


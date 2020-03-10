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
        Route::get('/user/vote', 'UserController@getVoteArticleByUserId'); // 点赞
        Route::post('/union/consume','UnionPayController@consume'); // 银联配置
    });

    // index
    Route::get('/home', 'IndexController@index');
    Route::post('/express', 'IndexController@express');
    Route::post('/wangyi/verify', 'IndexController@wangyiVerify'); // 网易易盾验证
    Route::get('/queue_demo', 'IndexController@queue_demo');
    Route::post('/wx_login', 'IndexController@wx_login'); // wxxcx获取信息

    // login register
    Route::post('/login', 'UserController@login');
    Route::post('/register', 'UserController@register');

    // hashids
    Route::post('/hash_ids/encode', 'HashIdsController@hashIdsEncode');
    Route::post('/hash_ids/decode', 'HashIdsController@hashIdsDecode');

    // news
    Route::group(['prefix' => '/article'], function () {
        Route::get('/list', 'ArticleController@getArticleList');
        Route::get('/rand', 'ArticleController@randGetArticle');
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

    // union
    Route::post('/union/validate/{txnTime}','UnionPayController@union_validate'); // 获取银联参数
    Route::post('/union/refund','UnionPayController@union_refund'); // 银联退款

});


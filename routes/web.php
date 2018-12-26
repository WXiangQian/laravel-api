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

Route::get('/swagger', function () {
    return view('vendor.l5-swagger.index', ['urlToDocs' => '/doc/json']);
});
Route::group(['prefix' => 'doc'], function () {
    Route::get('json', 'SwaggerController@getJSON');
});
Route::get('/wxq/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('/article', function () {
    return view('home.article.list');
});
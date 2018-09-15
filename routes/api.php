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
Route::group(['namespace' => 'Api'], function () {

    Route::get('/news', [
        'uses' => 'NewsController@index',
        'as' => 'news.list'
    ]);
    Route::post('/news/show', [
        'uses' => 'NewsController@show',
        'as' => 'news.show'
    ]);
    Route::post('/news/create', [
        'uses' => 'NewsController@create',
        'as' => 'news.create'
    ]);
    Route::put('/news/edit', [
        'uses' => 'NewsController@edit',
        'as' => 'news.edit'
    ]);
    Route::delete('/news/delete', [
        'uses' => 'NewsController@delete',
        'as' => 'news.delete'
    ]);
});

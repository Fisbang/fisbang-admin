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

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

Route::resource('articles', 'ArticleController');
Route::post('/articles/{id}/add/', 'ArticleController@addRecipient');
Route::post('/articles/{id}/addAll/', 'ArticleController@addAllRecipients');
Route::delete('/articles/{id}/remove/{user_id}', 'ArticleController@removeRecipient');
Route::delete('/articles/{id}/removeAll/', 'ArticleController@removeAll');

Route::resource('users', 'UserController');
Route::resource('questions', 'QuestionController');
Route::resource('messages', 'MessageController');
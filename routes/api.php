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
// Prefix: /v1
Route::group(['prefix' => 'v1', 'namespace' => 'API'], function () {
	
	// /v1/login
	Route::post('/login', 'UserController@authenticate');
	
	// /v1/refresh
	Route::get('/refresh', 'UserController@refresh');
	
	// /v1/register
	Route::post('/register', 'UserController@create');
	
	// Required authentication
	Route::group(['middleware' => 'jwt.auth'], function () {	
	
		// Prefix: /v1/user
		Route::group(['prefix' => 'user'], function () {
			// /v1/user/
			Route::get('/', 'UserController@details');
			// /v1/user/
			Route::put('/', 'UserController@update');
		});
		
		// Prefix: /v1/question
		Route::group(['prefix' => 'question'], function () {
			// /v1/question/
			Route::get('/', 'QuestionController@index');
			// /v1/question/{id}
			Route::get('/{id}', 'QuestionController@details');
			// /v1/question/markasanswered/{id}
			Route::get('markasanswered/{id}', 'QuestionController@markasanswered');
			// /v1/question/create
			Route::post('create', 'QuestionController@create');
		});
		
		// Prefix: /v1/message
		Route::group(['prefix' => 'message'], function () {
			// /v1/message/create
			Route::post('create', 'MessageController@create');
		});
		
		// Prefix: /v1/article
		Route::group(['prefix' => 'article'], function () {
			// /v1/article/
			Route::get('/', 'ArticleController@index');
			// /v1/article/{id}
			Route::get('/{id}', 'ArticleController@details');
			// /v1/article/{id}
			Route::put('/{id}', 'ArticleController@update');
			// /v1/article/create
			Route::post('create', 'ArticleController@create');
			// /v1/article/{id}
			Route::delete('/{id}', 'ArticleController@destroy');
		});
		
		// Prefix: /v1/building
		Route::group(['prefix' => 'building'], function () {
			// /v1/building/
			Route::get('/', 'BuildingController@index');
			// /v1/building/{id}
			Route::get('/{id}', 'BuildingController@details');
			// /v1/building/{id}
			Route::put('/{id}', 'BuildingController@update');
			// /v1/building/create
			Route::post('create', 'BuildingController@create');
			// /v1/building/{id}
			Route::delete('/{id}', 'BuildingController@destroy');
		});
		
		// Prefix: /v1/appliance
		Route::group(['prefix' => 'appliance'], function () {
			// /v1/appliance/
			Route::get('/', 'ApplianceController@index');
			// /v1/appliance/{id}
			Route::get('/{id}', 'ApplianceController@details');
			// /v1/appliance/{id}
			Route::put('/{id}', 'ApplianceController@update');
			// /v1/appliance/create
			Route::post('create', 'ApplianceController@create');
			// /v1/appliance/{id}
			Route::delete('/{id}', 'ApplianceController@destroy');
		});
		
		// Prefix: /v1/billing
		Route::group(['prefix' => 'billing'], function () {
			// /v1/billing/
			Route::get('/', 'BillingController@index');
			// /v1/billing/{id}
			Route::get('/{id}', 'BillingController@details');
			// /v1/billing/{id}
			Route::put('/{id}', 'BillingController@update');
			// /v1/billing/create
			Route::post('create', 'BillingController@create');
			// /v1/billing/{id}
			Route::delete('/{id}', 'BillingController@destroy');
		});
	});
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
//main routers
Route::apiResources(['user'=>'API\UserController']);
Route::apiResources(['master_transaction'=>'API\MasterTransactionController']);
Route::apiResources(['child_transaction'=>'API\ChildTransactionController']);
Route::apiResources(['client'=>'API\ClientController']);
Route::apiResources(['color'=>'API\ColorController']);
Route::apiResources(['product'=>'API\ProductController']);

//Search functions
Route::get('finduser','API\UserController@search');
Route::get('findinvoice','API\MasterTransactionController@search');
Route::get('findproduct','API\ProductController@search');
Route::get('findclient','API\ClientController@search');
Route::get('findcolor','API\ColorController@search');
Route::get('findproduct','API\ProductController@search');

//Fetch 'n Populate
Route::get('get_user_name','API\UserController@get_user_name');
Route::get('get_number','API\UserController@get_number');
Route::get('get_product','API\ProductController@get_product');
Route::get('get_clients','API\ClientController@get_clients');
Route::get('get_colors','API\ColorController@get_colors');
Route::get('get_client_data','API\ClientController@get_client_data');
Route::get('get_product_data','API\ProductController@get_product_data');



//Chart
Route::get('chart_data','API\UserController@chart_data');

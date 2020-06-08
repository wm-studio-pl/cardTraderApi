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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/', 'Controller@main');
Route::post('login', 'API\UserController@login')->name('login');
Route::post('users/register', 'API\UserController@register');
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('users/details', 'API\UserController@details');
    Route::put('users/cards/add/{id}', 'API\UserController@addCard');
    Route::put('users/cards/sub/{id}', 'API\UserController@subCard');

    Route::post('categories', 'API\CategoryController@store'); //new category
    Route::put('categories', 'API\CategoryController@store'); //edit->save category
    Route::delete('categories/{id}', 'API\CategoryController@destroy'); //delete category
    Route::post('subcategories', 'API\SubcategoryController@store'); //new subcategory
    Route::put('subcategories', 'API\SubcategoryController@store'); //edit->save subcategory
    Route::delete('subcategories/{id}', 'API\SubcategoryController@destroy'); //delete subcategory
    Route::post('cards', 'API\CardController@store'); //new card
    Route::put('cards', 'API\CardController@store'); //edit->save card
    Route::delete('cards/{id}', 'API\CardController@destroy'); //delete card
});
Route::any('test', 'API\UserController@test');

Route::get('categories', 'API\CategoryController@index');
Route::get('categories/{id}', 'API\CategoryController@show');
Route::get('subcategories', 'API\SubcategoryController@index');
Route::get('subcategories/{id}', 'API\SubcategoryController@show');
Route::get('cards', 'API\CardController@index');
Route::get('cards/{id}', 'API\CardController@show');
Route::get('cards/u/{user_id}', 'API\CardController@showUserCards');
Route::get('cards/c/{category_id}', 'API\CardController@showInCategory');
Route::get('cards/{category_id}/{subcategory_id}', 'API\CardController@showInCategory');




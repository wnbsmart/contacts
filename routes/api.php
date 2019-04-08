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

Route::middleware('auth:api')->namespace('Api')->group(function () {
    Route::get('contacts', 'ContactApiController@index')->name('contacts.api.index');
    Route::get('contacts/favourites', 'ContactApiController@showFavourites')->name('contacts.api.show.favourite');
//    Route::get('contacts/edit', 'ContactApiController@edit')->name('contacts.api.edit');
    Route::post('contacts', 'ContactApiController@store')->name('contacts.api.store');
    Route::get('contacts/{id}', 'ContactApiController@show')->name('contacts.api.show');
    Route::put('contacts/{id}', 'ContactApiController@update')->name('contacts.api.update');
    Route::delete('contacts/{id}', 'ContactApiController@destroy')->name('contacts.api.delete');

    Route::get('contacts/search/all/{input}', 'ContactApiController@searchAllContacts');
    Route::get('contacts/search/favourite/{input}', 'ContactApiController@searchFavouriteContacts');
});

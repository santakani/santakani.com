<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/', 'DesignerController@index');

    Route::get('setting', 'UserController@setting');

    Route::get('notification', 'UserController@notification');

    Route::resource('user', 'UserController', ['except' => [
        'create', 'store'
    ]]);

    Route::resource('image', 'ImageController', ['except' => [
        'create', 'edit', 'update'
    ]]);

    Route::resource('tag', 'TagController');

    Route::resource('country', 'CountryController');

    Route::resource('city', 'CityController');

    Route::resource('designer', 'DesignerController');

    Route::resource('place', 'PlaceController');

    Route::resource('story', 'StoryController');
});

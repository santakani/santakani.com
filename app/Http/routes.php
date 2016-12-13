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

    Route::get('auth/facebook', 'Auth\OAuthController@redirectToFacebook');
    Route::get('auth/facebook/callback', 'Auth\OAuthController@handleFacebookCallback');
    Route::get('auth/google', 'Auth\OAuthController@redirectToGoogle');
    Route::get('auth/google/callback', 'Auth\OAuthController@handleGoogleCallback');
    Route::get('auth/twitter', 'Auth\OAuthController@redirectToTwitter');
    Route::get('auth/twitter/callback', 'Auth\OAuthController@handleTwitterCallback');

    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index');
    Route::get('/home1a', 'HomeController@index1a');
    Route::get('/home1b', 'HomeController@index1b');
    Route::get('/home1c', 'HomeController@index1c');
    Route::get('/home2a', 'HomeController@index2a');
    Route::get('/home2b', 'HomeController@index2b');

    Route::get('about', 'PageController@about');
    Route::get('privacy', 'PageController@privacy');
    Route::get('terms', 'PageController@terms');

    Route::get('sitemap', 'SitemapController@index');

    Route::get('setting', 'SettingController@profile');
    Route::get('setting/profile', 'SettingController@profile');
    Route::get('setting/account', 'SettingController@account');
    Route::get('setting/page', 'SettingController@page');
    Route::get('setting/story', 'SettingController@story');

    Route::match(['post', 'put', 'patch'], 'setting', 'SettingController@update');

    Route::get('trash', 'TrashController@index');
    Route::get('trash/designer', 'TrashController@designer');
    Route::get('trash/design', 'TrashController@design');
    Route::get('trash/place', 'TrashController@place');
    Route::get('trash/story', 'TrashController@story');

    Route::resource('user', 'UserController', ['except' => [
        'create', 'store'
    ]]);

    Route::resource('image', 'ImageController', ['except' => [
        'create', 'edit', 'update'
    ]]);

    Route::resource('like', 'LikeController', ['only' => ['store', 'destroy']]);

    Route::resource('tag', 'TagController');

    Route::resource('country', 'CountryController');

    Route::resource('city', 'CityController');

    Route::resource('designer', 'DesignerController');
    Route::resource('design', 'DesignController');

    Route::resource('place', 'PlaceController');

    Route::resource('story', 'StoryController');

    // Admin panel
    Route::group(['middleware' => ['auth', 'admin'], 'namespace' => 'Admin', 'prefix' => 'admin'], function() {
        Route::get('/', 'HomeController@index');
        Route::get('log/activity', 'ActivityLogController@index');
        Route::get('user', 'HomeController@user');
        Route::get('image', 'HomeController@image');
    });

    Route::get('help', 'HelpController@index');
});


/*
|--------------------------------------------------------------------------
| API Routes (version 1)
|--------------------------------------------------------------------------
|
| This route group applies the "api" middleware group to every route
| it contains. The "api" middleware group is defined in your HTTP
| kernel and includes throttle (limit API rates by 60 times per second) and
| auth through API rather than session.
|
*/

Route::group(['middleware' => 'api', 'prefix' => 'api/v1'], function () {


});

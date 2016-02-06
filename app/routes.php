<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// Filters
Route::filter('auth', function()
{
    if (Auth::viaRemember())
    {
        return Redirect::to('/login');
    }
});


// Route::get('/', 'UserController@feed');
Route::get('/', array('before' => 'auth', 'uses' => 'UserController@feed'));
Route::get('/settings', array('before' => 'auth', 'uses' => 'UserController@showSettings'));

Route::get('/about', 'ViewsController@about');

Route::get('/login', 'ViewsController@login');
Route::post('/login', 'UserController@authenticate');

Route::get('/signup', 'UserController@create');
Route::post('/signup', 'UserController@store');

Route::post('/getposter', 'MediaController@getPoster');

Route::get('/media/{imdbId}', 'MediaController@show');

Route::post('/search', 'MediaController@search');

Route::any('/usersearch', 'UserController@search');

Route::any('/postview', 'PostController@store');
Route::any('/deletecomment/{commentId}', 'PostController@deletePost');

Route::any('/addtoqueue/{imdbId}', 'QueueController@store');
Route::any('/removefromqueue/{imdbId}', 'QueueController@destroy');
Route::get('/queue', 'QueueController@show');

Route::get('/feed', 'UserController@feed');

Route::any('/likePost/{post_id}', 'UserController@likePost');
Route::any('/unlikePost/{post_id}', 'UserController@unlikePost');

Route::get('/profile/{user_id}', 'UserController@show');

Route::any('/follow/{user_id}', 'UserController@follow');
Route::any('/unfollow/{user_id}', 'UserController@unfollow');

Route::get('/followers', 'UserController@showFollowers');
Route::get('/following', 'UserController@showFollowing');

Route::post('/storenewemail', 'UserController@storeNewEmail');
Route::post('/checkpassword', 'UserController@checkPassword');
Route::post('/storenewpassword', 'UserController@storeNewPassword');
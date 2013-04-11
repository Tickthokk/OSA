<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

// Home
Route::get('/', array('uses' => 'home@index'));

// About
Route::get('about', function() {
	return View::make('about');
});

// Users
Route::any('user/(:any?)', array('uses' => 'user@view'));

// Authentication
Route::controller('auth');

// Games

// Game Filtering
Route::any('games/search', 'games@search');
Route::any('games/(:any?)/(:any?)/(:any?)', 'games@index');

// Game specific
Route::any('game/(:num)', array('uses' => 'game@view'));
Route::controller('game');

// Achievements
Route::any('achievement/(:num)', 'achievement@view');
Route::controller('achievement');

// Achievement Comments
Route::any('comment/(:any)', 'comment@comment');

// Special Images
Route::controller('images');

// Flags
Route::controller('flag');



// TEST ROUTES
Route::get('disqus', 'home@disqus');

// Event::listen('laravel.query', function($sql, $bindings, $time) {
// 	echo $sql . '<br>';//, $bindings, $time);
// 	var_dump($bindings);
// });

//require_once path('sys') . 'cli' . DS . 'dependencies' . EXT;
//Laravel\CLI\Command::run(array('migrate'));

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

// 400 >> Bad Request >> The request cannot be fulfilled due to bad syntax
Event::listen('400', function($str = NULL)
{
	// If the request originates from AJAX, specialize the error
	if (Request::ajax())
		return Response::json(array('error' => true, 'message' => $str), 400);

	return Response::error('400');
});

// 401 >> Unauthorized >> Similar to 403 Forbidden, but specifically for use when authentication is required and has failed or has not yet been provided.
Event::listen('401', function($str = NULL)
{
	if (Request::ajax())
		return Response::json(array('error' => true, 'message' => $str), 401);

	return Response::error('401');
});

// 403 >> Forbidden >> The request was a valid request, but the server is refusing to respond to it. Unlike a 401 Unauthorized response, authenticating will make no difference.
Event::listen('403', function($str = NULL)
{
	if (Request::ajax())
		return Response::json(array('error' => true, 'message' => $str), 403);

	return Response::error('403');
});

// 404 >> Not Found >> The requested resource could not be found but may be available again in the future.
Event::listen('404', function($str = NULL)
{
	if (Request::ajax())
		return Response::json(array('error' => true, 'message' => $str), 404);

	return Response::error('404');
});

// 500 >> Internal Server Error >> A generic error message, given when no more specific message is suitable.
Event::listen('500', function($str = NULL)
{
	if (Request::ajax())
		return Response::json(array('error' => true, 'message' => $str), 500);

	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});
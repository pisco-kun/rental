<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

	# this route for admin
	$router->post('users/login', 'Users\UsersController@login'); # login
	$router->post('users/register', 'Users\UsersController@register'); # login

	$router->group(['prefix' => 'users', 'middleware' => 'auth'], function () use ($router) {
	});

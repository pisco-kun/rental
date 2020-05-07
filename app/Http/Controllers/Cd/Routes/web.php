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
	$router->group(['prefix' => 'cd', 'middleware' => 'auth:admin'], function () use ($router) {
		$router->get('/', 'Cd\CdController@index');
		$router->post('save', 'Cd\CdController@saveData');
	});

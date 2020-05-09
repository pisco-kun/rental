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
	$router->group(['prefix' => 'cd_category', 'middleware' => 'auth:admin'], function () use ($router) {
		$router->post('save', 'Cd_category\Cd_categoryController@saveData'); #save data
		$router->get('/edit/{uuid}', 'Cd_category\Cd_categoryController@editData'); # edit data
		$router->put('update/{uuid}', 'Cd_category\Cd_categoryController@updateData'); # update data
		$router->delete('delete/{uuid}', 'Cd_category\Cd_categoryController@deleteData'); # delete data
		$router->post('quick_update', 'Cd_category\Cd_categoryController@quick_update'); # quick update data
	});

	# this route for admin and users
	$router->group(['prefix'=>'cd_category'],function () use ($router) {
		$router->get('/', 'Cd_category\Cd_categoryController@index'); # listing
		$router->get('detail/{uuid}', 'Cd_category\Cd_categoryController@detail'); # detail data
	});

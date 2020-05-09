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
		$router->post('save', 'Cd\CdController@saveData'); #save data
		$router->get('/edit/{uuid}', 'Cd\CdController@editData'); # edit data
		$router->put('update/{uuid}', 'Cd\CdController@updateData'); # update data
		$router->delete('delete/{uuid}', 'Cd\CdController@deleteData'); # delete data
		$router->post('quick_update', 'Cd\CdController@quick_update'); # quick update data
		$router->get('/', 'Cd\CdController@index'); # listing
		$router->get('detail/{uuid}', 'Cd\CdController@detail'); # detail data
		$router->post('stock', 'Cd\CdController@checkStock'); # run check stock
	});

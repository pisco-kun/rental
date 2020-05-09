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
	$router->group(['prefix' => 'orders', 'middleware' => 'auth:admin'], function () use ($router) {
		$router->get('/', 'Orders\OrdersController@index'); # listing
		$router->post('/cd', 'Orders\OrdersController@stepOrders'); # orders cd
		$router->post('/cd_return', 'Orders\OrdersController@ordersReturn'); # orders cd
		$router->get('/detail/{uuid}', 'Orders\OrdersController@detail'); # detail
	});

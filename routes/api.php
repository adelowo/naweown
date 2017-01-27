<?php

use Illuminate\Routing\Router;

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

/**
 * @var \Illuminate\Routing\Router $router
 */

//Bite me.
$router->group(['prefix' => 'v1'], function (Router $router) {

    $router->get('users', 'Api\One\UserController@index')
        ->name('api.users');

    $router->get('users/@{moniker}', 'Api\One\UserController@profile')
        ->name('api.users.profile');

    $router->get('users/@{moniker}/items', 'Api\One\UserController@items')
        ->name('api.users.profile');

    $router->resource('items', 'Api\One\ItemController', ['only' => ['index', 'show']]);

});

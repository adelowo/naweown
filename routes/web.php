<?php


/**
 * @var \Illuminate\Routing\Router $router
 */

$router->get('/', function () {
    return view('welcome');
})->name('home');

$router->get("login", 'Auth\LoginController@showLoginForm')
    ->name("login");

$router->post("login", 'Auth\LoginController@postlogin');

$router->get("login/{token}", 'Auth\LoginController@login')
    ->name('login.token');

$router->get("register", 'Auth\RegisterController@showRegistrationForm')
    ->name('register');

$router->post("register", 'Auth\RegisterController@register');

$router->get('account/activate/{token}', 'Auth\AccountActivationController@activate')
    ->name("activate");

$router->get("logout", 'Auth\LoginController@logout')
    ->name("logout");

$router->get('@{moniker}', 'UserController@show')
    ->name('users.profile');

$router->get('users', 'UserController@index')
    ->name('users.all');

$router->resource('items', 'ItemController');

$router->resource('category', 'CategoryController', ['only' => ['index', 'show']]);

$router->get('tags', 'TagController@index', ['only' => ['index', 'show']]);




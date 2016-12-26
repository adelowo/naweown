<?php


/**
 * @var \Illuminate\Routing\Router $router
 */

$router->get('/', function () {
    return view('welcome');
});

$router->auth();

<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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


$router->group(['prefix'=>'auth/api', 'middleware' => 'auth'], 
                function() use($router){
    $router->get('/items', 'ProductController@index');
    //$router->post('/items', 'ProductController@create');
    $router->post('items', ['middleware' => 'auth:create:products', 'uses' => 'ProductController@create']);
    $router->get('/items/{id}', 'ProductController@show');
    $router->put('/items/{id}', 'ProductController@update');
    $router->delete('/items/{id}', 'ProductController@destroy');
});


$router->group(['prefix'=>'api'], function() use($router){
    // products
    $router->get('/items', 'ProductController@index');
    $router->post('/items', 'ProductController@create');
    $router->get('/items/{id}', 'ProductController@show');
    $router->put('/items/{id}', 'ProductController@update');
    $router->delete('/items/{id}', 'ProductController@destroy');

    // customers
    $router->get('/customers', 'CustomerController@index');

    // orders
    $router->get('/orders', 'OrderController@index');
    $router->post('/orders', 'OrderController@create');

});


$router->get('/', function () use ($router) {
    return $router->app->version();
});

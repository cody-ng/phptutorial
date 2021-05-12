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
    $router->post('items', ['middleware' => 'auth:create:products', 'uses' => 'ProductController@create']);
    $router->get('/items/{id:[0-9]+}', 'ProductController@show');
    $router->put('/items/{id:[0-9]+}', 'ProductController@update');
    $router->delete('/items/{id:[0-9]+}', 'ProductController@destroy');
});


$router->group(['prefix'=>'api'], function() use($router){
    
    // products
    $router->get('/products', [
        'as' => 'getProducts', 'uses' => 'ProductController@index']);
    $router->post('/products', [
        'as' => 'createProduct', 'uses' => 'ProductController@create']);
    $router->get('/products/{id:[0-9]+}', [
        'as' => 'getOneProduct', 'uses' => 'ProductController@show']);
    $router->put('/products/{id:[0-9]+}', [
        'as' => 'updateProducts', 'uses' => 'ProductController@update']);
    $router->delete('/products/{id:[0-9]+}', [
        'as' => 'deleteProducts', 'uses' => 'ProductController@destroy']);

    // customers
    $router->get('/customers', [
        'as' => 'customers', 'uses' => 'CustomerController@index']);

    // orders
    $router->get('/orders', [
        'as' => 'orders', 'uses' => 'OrderController@index']);
    $router->post('/orders', [
        'as' => 'createOrders', 'uses' => 'OrderController@create']);
    $router->get('/orders/{id:[0-9]+}', [
        'as' => 'orderDetails', 'uses' => 'OrderController@orderDetails']);

});


$router->get('/', function () use ($router) {
    return $router->app->version();
});

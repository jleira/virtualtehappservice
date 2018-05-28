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

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post(
    'auth/login', 
    [
       'uses' => 'AuthController@authenticate'
    ]
);

$router->post('api/auth/login', 'AuthController@login');
$router->post('api/auth/register','AuthController@register');
$router->post('/api/find/mascotasvisitante', 'FinderController@mascotasvisitante');    

$router->group(
    ['middleware' => 'jwt.auth'], 
    function() use ($router) {
        $router->get('users', 'AuthController@');
        $router->get('/api/pets/mismascotas/{id}', 'PetsController@mismascotas');
        $router->get('/api/mydata', 'AuthController@mydata');
        $router->post('/api/pets/agregar', 'PetsController@crearmascota');    
        $router->post('/api/find/people', 'FinderController@people');    
        $router->post('/api/find/mascotas', 'FinderController@mascotas');    
        $router->get('/api/finduser/findbyid/{id}', 'AuthController@findbyid');
        $router->get('/api/follow/seguir/{id}', 'AuthController@seguir');
        $router->get('/api/follow/dejardeseguir/{id}', 'AuthController@dejardeseguir');
        $router->get('/api/chat/mensajes/{recibe}', 'ChatController@mensajes');
        $router->post('/api/chat/mensajes/guardar', 'ChatController@insertmsj');
        
    }
);


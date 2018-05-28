<?php

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'LocationComponent',
    ['path' => '/locate'],
    function (RouteBuilder $routes) 
    {
        $routes->get('/', ['controller' => 'Component', 'action' => 'status']);
        $routes->get('/companies', ['controller' => 'Component', 'action' => 'view']);
        $routes->get('/company/:id', ['controller' => 'Component', 'action' => 'view']);
        $routes->get('/:query', ['controller' => 'Component', 'action' => 'locate'])
            ->setPass(['query', 'postcode']);
        // $routes->fallbacks(DashedRoute::class);
    }
);

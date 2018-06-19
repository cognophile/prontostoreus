<?php

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'LocationComponent',
    ['path' => '/locate'],
    function (RouteBuilder $routes) 
    {
        $routes->get('/', ['controller' => 'Companies', 'action' => 'status']);
        $routes->get('/:query', ['controller' => 'Companies', 'action' => 'locate'])
            ->setPass(['query', 'postcode']);
        // $routes->fallbacks(DashedRoute::class);
    }
);

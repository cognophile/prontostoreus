<?php

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'LocationComponent',
    ['path' => '/locations'],
    function (RouteBuilder $routes) 
    {
        $routes->connect('/', ['controller' => 'Companies', 'action' => 'status']);
        $routes->connect('/:postcode', ['controller' => 'Companies', 'action' => 'locate'])
            ->setPass(['postcode']);
        
            // $routes->fallbacks(DashedRoute::class);
    }
);

<?php

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'LocationComponent',
    ['path' => '/locate'],
    function (RouteBuilder $routes) {

        $routes->get('/', ['controller' => 'Component', 'action' => 'status']);
        // $routes->fallbacks(DashedRoute::class);
    }
);

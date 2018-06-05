<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'CustomerDetailsComponent',
    ['path' => '/details'],
    function (RouteBuilder $routes) {
        $routes->get('/', ['controller' => 'Component', 'action' => 'status']);
        $routes->post('/', ['controller' => 'Component', 'action' => 'add']);
        // $routes->fallbacks(DashedRoute::class);
    }
);

<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'CustomerDetailsComponent',
    ['path' => '/details'],
    function (RouteBuilder $routes) {
        $routes->get('/', ['controller' => 'Customers', 'action' => 'status']);
        $routes->post('/', ['controller' => 'Customers', 'action' => 'add']);
        // $routes->fallbacks(DashedRoute::class);
    }
);

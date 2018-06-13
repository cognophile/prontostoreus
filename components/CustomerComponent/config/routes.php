<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'CustomerComponent',
    ['path' => '/customer'],
    function (RouteBuilder $routes) {
        $routes->get('/', ['controller' => 'Customers', 'action' => 'status']);
        $routes->post('/', ['controller' => 'Customers', 'action' => 'add']);
        $routes->get('/:query', ['controller' => 'Customers', 'action' => 'view'])
            ->setPass(['query', 'id']);       
        
        // $routes->fallbacks(DashedRoute::class);
    }
);

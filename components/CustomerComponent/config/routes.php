<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'CustomerComponent',
    ['path' => Configure::read('Api.Scope') . 'customers'],
    function (RouteBuilder $routes) {
        $routes->connect('/', ['controller' => 'Customers', 'action' => 'status']);
        $routes->connect('/add', ['controller' => 'Customers', 'action' => 'add']);       
        
        // $routes->fallbacks(DashedRoute::class);
    }
);

<?php

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'LocationComponent',
    ['path' => Configure::read('Api.Scope') . 'locations'],
    function (RouteBuilder $routes) 
    {
        $routes->connect('/', ['controller' => 'Companies', 'action' => 'status']);
        $routes->connect('/:postcode', ['controller' => 'Companies', 'action' => 'locate'])
            ->setPass(['postcode']);
        
            // $routes->fallbacks(DashedRoute::class);
    }
);

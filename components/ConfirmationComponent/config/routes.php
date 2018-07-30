<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'ConfirmationComponent',
    ['path' => '/confirmations'],
    function (RouteBuilder $routes) {
        $routes->connect('/', ['controller' => 'Confirmations', 'action' => 'status']);
        $routes->connect('/applications/:application_id/update', ['controller' => 'Confirmations', 'action' => 'acceptTerms'])
            ->setPass(['application_id']);
        
        // $routes->fallbacks(DashedRoute::class);
    }
);

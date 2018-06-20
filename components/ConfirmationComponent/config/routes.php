<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'ConfirmationComponent',
    ['path' => '/confirm'],
    function (RouteBuilder $routes) {
        $routes->get('/', ['controller' => 'Confirmations', 'action' => 'status']);
        $routes->post('/', ['controller' => 'Confirmations', 'action' => 'acceptTerms']);
        // $routes->fallbacks(DashedRoute::class);
    }
);

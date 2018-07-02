<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'InvoiceComponent',
    ['path' => '/invoices'],
    function (RouteBuilder $routes) {
        $routes->get('/', ['controller' => 'Invoice', 'action' => 'status']);

        $routes->get('/:id', ['controller' => 'Invoice', 'action' => 'status'])
            ->setPass(['invoice_id']);

        $routes->get('/applications/:application_id', ['controller' => 'Invoice', 'action' => 'status'])
            ->setPass(['application_id']);

        // $routes->fallbacks(DashedRoute::class);
    }
);
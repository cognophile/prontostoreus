<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'InvoiceComponent',
    ['path' => '/invoices'],
    function (RouteBuilder $routes) {
        $routes->get('/', ['controller' => 'Invoice', 'action' => 'status']);

        $routes->get('/:invoice_id/', ['controller' => 'Invoice', 'action' => 'status'])
            ->setPass(['invoice_id']);

        $routes->get('/applications/:application_id/', ['controller' => 'Invoice', 'action' => 'view'])
            ->setPass(['application_id']);

        $routes->get('/applications/:application_id/customer/', ['controller' => 'Invoice', 'action' => 'getApplicationCustomer'])
            ->setPass(['application_id']);

        $routes->get('/applications/:application_id/company/', ['controller' => 'Invoice', 'action' => 'getApplicationCompany'])
            ->setPass(['application_id']);

        $routes->get('/applications/:application_id/meta/', ['controller' => 'Invoice', 'action' => 'getApplicationMetadata'])
            ->setPass(['application_id']);
        
        $routes->get('/applications/:application_id/lines/', ['controller' => 'Invoice', 'action' => 'getApplicationLines'])
            ->setPass(['application_id']);

        // $routes->fallbacks(DashedRoute::class);
    }
);
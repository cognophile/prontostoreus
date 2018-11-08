<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'InvoiceComponent',
    ['path' => Configure::read('Api.Scope') . 'invoices'],
    function (RouteBuilder $routes) {
        $routes->connect('/', ['controller' => 'Invoice', 'action' => 'status']);

        $routes->connect('/applications/:application_id/add', ['controller' => 'Invoice', 'action' => 'add'])
            ->setPass(['application_id']);

        $routes->connect('/applications/:application_id/customer/', ['controller' => 'Invoice', 'action' => 'getApplicationCustomer'])
            ->setPass(['application_id']);

        $routes->connect('/applications/:application_id/company/', ['controller' => 'Invoice', 'action' => 'getApplicationCompany'])
            ->setPass(['application_id']);
        
        $routes->connect('/applications/:application_id/lines/', ['controller' => 'Invoice', 'action' => 'getApplicationLines'])
            ->setPass(['application_id']);

        $routes->connect('/applications/:application_id/data', ['controller' => 'Invoice', 'action' => 'getInvoiceDataByApplication'])
            ->setPass(['application_id']);

        $routes->connect('/applications/:application_id/', ['controller' => 'Invoice', 'action' => 'produceInvoice'])
            ->setPass(['application_id']);
            
        // $routes->fallbacks(DashedRoute::class);
    }
);
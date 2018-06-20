<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'ApplicationComponent',
    ['path' => '/apply'],
    function (RouteBuilder $routes) {
        // TODO: Update all component routes to restful resources?
        $routes->get('/company/:company_id', ['controller' => 'Application', 'action' => 'listFurnishings'])
            ->setPass(['company_id', 'companyId']);
        
        $routes->get('/company/:company_id/customer/:customer_id', ['controller' => 'Application', 'action' => 'view'])
            ->setPass(['company_id', 'companyId'], ['customer_id', 'customerId']);
        
        $routes->post('/', ['controller' => 'Application', 'action' => 'add']);
        // $routes->fallbacks(DashedRoute::class);
    }
);

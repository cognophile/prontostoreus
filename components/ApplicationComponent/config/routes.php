<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'ApplicationComponent',
    ['path' => '/apply'],
    function (RouteBuilder $routes) {
        // TODO: Update all component routes to restful resources?
        $routes->get('/', ['controller' => 'Applications', 'action' => 'status']);

        // Rooms
        $routes->get('/room', ['controller' => 'Rooms', 'action' => 'fetchRoomList']);
        $routes->get('/room/:room_id', ['controller' => 'Rooms', 'action' => 'fetchRoomList'])
            ->setPass(['room_id']);

        // Furnishings
        $routes->get('/room/:room_id/furnishing', ['controller' => 'Furnishings', 'action' => 'fetchFurnishingListByRoom'])
            ->setPass(['room_id']);

        $routes->get('/room/:room_id/furnishing/:furnishing_id/', ['controller' => 'Furnishings', 'action' => 'fetchFurnishingSize'])
            ->setPass(['room_id', 'furnishing_id']);

        $routes->get('/company/:company_id/furnishing/:furnishing_id/', ['controller' => 'CompanyFurnishingRates', 'action' => 'fetchPriceByItem'])
            ->setPass(['company_id', 'furnishing_id']);

        // $routes->get('/company/:company_id', ['controller' => 'Applications', 'action' => 'listFurnishings'])
        //     ->setPass(['company_id', 'companyId']);
        
        // $routes->get('/company/:company_id/customer/:customer_id', ['controller' => 'Applications', 'action' => 'view'])
        //     ->setPass(['company_id', 'companyId'], ['customer_id', 'customerId']);
        
        // $routes->post('/', ['controller' => 'Applications', 'action' => 'add']);
        // $routes->fallbacks(DashedRoute::class);
    }
);

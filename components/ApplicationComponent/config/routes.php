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
        $routes->get('/room', ['controller' => 'Applications', 'action' => 'fetchRoomList']);
        $routes->get('/room/:room_id', ['controller' => 'Applications', 'action' => 'fetchRoomList'])
            ->setPass(['room_id']);

        // Furnishings
        $routes->get('/room/:room_id/furnishing', ['controller' => 'Applications', 'action' => 'fetchFurnishingListByRoom'])
            ->setPass(['room_id']);

        $routes->get('/room/:room_id/furnishing/:furnishing_id', ['controller' => 'Applications', 'action' => 'fetchFurnishingSize'])
            ->setPass(['room_id', 'furnishing_id']);

        // $routes->get('/company/:company_id', ['controller' => 'Applications', 'action' => 'listFurnishings'])
        //     ->setPass(['company_id', 'companyId']);
        
        // $routes->get('/company/:company_id/customer/:customer_id', ['controller' => 'Applications', 'action' => 'view'])
        //     ->setPass(['company_id', 'companyId'], ['customer_id', 'customerId']);
        
        // $routes->post('/', ['controller' => 'Applications', 'action' => 'add']);
        // $routes->fallbacks(DashedRoute::class);
    }
);

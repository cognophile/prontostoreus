<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'ApplicationComponent',
    ['path' => '/applications'],
    function (RouteBuilder $routes) {
        // TODO: Update all component routes to restful resources with OPTIONS pre-flight check and p-style before methods
        $routes->get('/', ['controller' => 'Applications', 'action' => 'status']);

        // Rooms
        $routes->get('/room', ['controller' => 'Rooms', 'action' => 'fetchRoomList']);
        $routes->get('/room/:room_id/', ['controller' => 'Rooms', 'action' => 'fetchRoomList'])
            ->setPass(['room_id']);

        // Furnishings
        $routes->get('/room/:room_id/furnishing/', ['controller' => 'Furnishings', 'action' => 'fetchFurnishingListByRoom'])
            ->setPass(['room_id']);

        $routes->get('/room/:room_id/furnishing/:furnishing_id/', ['controller' => 'Furnishings', 'action' => 'fetchFurnishingSize'])
            ->setPass(['room_id', 'furnishing_id']);

        // CompanyFurnishingRates
        $routes->get('/company/:company_id/furnishing/:furnishing_id/', ['controller' => 'CompanyFurnishingRates', 'action' => 'fetchPriceByItem'])
            ->setPass(['company_id', 'furnishing_id']);

        // Applications
        $routes->post('/add', ['controller' => 'Applications', 'action' => 'add']);

        $routes->post('/:application_id/edit/', ['controller' => 'Applications', 'action' => 'edit'])
            ->setPass(['application_id']);

        // $routes->fallbacks(DashedRoute::class);
    }
);

<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'ApplicationComponent',
    ['path' => '/applications'],
    function (RouteBuilder $routes) {
        // TODO: Update all component routes to restful resources with OPTIONS pre-flight check and p-style before methods
        $routes->connect('/', ['controller' => 'Applications', 'action' => 'status']);

        // Rooms
        $routes->connect('/room', ['controller' => 'Rooms', 'action' => 'fetchRoomList']);
        $routes->connect('/room/:room_id/', ['controller' => 'Rooms', 'action' => 'fetchRoomList'])
            ->setPass(['room_id']);

        // Furnishings
        $routes->connect('/room/:room_id/furnishing/', ['controller' => 'Furnishings', 'action' => 'fetchFurnishingListByRoom'])
            ->setPass(['room_id']);

        $routes->connect('/room/:room_id/furnishing/:furnishing_id/', ['controller' => 'Furnishings', 'action' => 'fetchFurnishingSize'])
            ->setPass(['room_id', 'furnishing_id']);

        // CompanyFurnishingRates
        $routes->connect('/company/:company_id/furnishing/:furnishing_id/', ['controller' => 'CompanyFurnishingRates', 'action' => 'fetchPriceByItem'])
            ->setPass(['company_id', 'furnishing_id']);

        // Applications
        $routes->connect('/add', ['controller' => 'Applications', 'action' => 'add']);

        $routes->connect('/:application_id/edit/', ['controller' => 'Applications', 'action' => 'edit'])
            ->setPass(['application_id']);

        // $routes->fallbacks(DashedRoute::class);
    }
);

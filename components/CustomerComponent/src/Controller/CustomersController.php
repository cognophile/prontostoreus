<?php

namespace CustomerComponent\Controller;

use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

use Prontostoreus\Api\Controller\AbstractApiController;

class CustomersController extends AbstractApiController
{
    public function initialize() 
    {
        parent::initialize();        
        $this->loadModel('CustomerComponent.Addresses');
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], "Customer base: {$message}", Configure::read('Api.Routes.Customers'));
    }

    public function add() 
    {
        return $this->universalAdd($this->Customers, true);
    }
}

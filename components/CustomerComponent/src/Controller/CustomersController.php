<?php

namespace CustomerComponent\Controller;

use Cake\ORM\TableRegistry;
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
        $this->respondSuccess([], "Customer base: {$message}");
    }

    public function add() 
    {
        return parent::universalAdd($this->Customers, true);
    }

    public function view($id = null) 
    {

    }

    public function edit() 
    {
        
    }

    public function remove() 
    {
        
    }
}

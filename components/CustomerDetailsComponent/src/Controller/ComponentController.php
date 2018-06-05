<?php

namespace CustomerDetailsComponent\Controller;

use Prontostoreus\Api\Controller\CycleController;

class ComponentController extends CycleController
{
    public function initialize() 
    {
        parent::initialize();
        $this->loadModel('CustomerDetailsComponents.Customers');
        $this->loadModel('CustomerDetailsComponents.Addresses');
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], "Customer details base: {$message}");
    }

    public function add() 
    {
        parent::universalAdd($this->Customers);
    }

    public function view() 
    {
        
    }

    public function edit() 
    {
        
    }

    public function remove() 
    {
        
    }
}

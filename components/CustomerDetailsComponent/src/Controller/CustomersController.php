<?php

namespace CustomerDetailsComponent\Controller;

use Prontostoreus\Api\Controller\CycleController;

class CustomersController extends CycleController
{
    private $relations = ["Addresses"];

    public function initialize() 
    {
        parent::initialize();
        $this->loadModel('CustomerDetailsComponent.Customers');
        $this->loadModel('CustomerDetailsComponent.Addresses');
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], "Customer details base: {$message}");
    }

    public function add() 
    {
        $this->setRelated($this->relations);
        return parent::universalAdd($this->Customers);
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

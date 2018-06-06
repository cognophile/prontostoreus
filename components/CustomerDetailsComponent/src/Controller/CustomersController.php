<?php

namespace CustomerDetailsComponent\Controller;

use Prontostoreus\Api\Controller\CycleController;

class CustomersController extends CycleController
{
    private $customersAssociations = ["Addresses" => ['validate' => false]];

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
        $this->setRelations($this->customersAssociations);
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

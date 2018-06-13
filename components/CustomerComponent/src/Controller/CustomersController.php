<?php

namespace CustomerComponent\Controller;

use Cake\ORM\TableRegistry;
use Prontostoreus\Api\Controller\CycleController;

class CustomersController extends CycleController
{
    private $customersAssociations = ['Addresses'];

    public function initialize() 
    {
        parent::initialize();
        
        $this->setAssociations($this->customersAssociations);
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

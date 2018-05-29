<?php

namespace LocationComponent\Controller;

use Prontostoreus\Api\Controller\CycleController;
use Cake\ORM\TableRegistry;

class ComponentController extends CycleController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('LocationComponent.Companies');
        $this->loadModel('LocationComponent.Addresses');
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], "Location Base: {$message}");
    }

    public function view($options = null)
    {   
        // if options is empty
            // send back all 

        // if options is numeric
            // send single back by id        
    }   

    public function locate(string $postcode)
    {
        $results = $this->Companies->find('byPostcode', ['postcode' => $postcode]);
        
        // Should this return results instead?
        if (!empty($results)) {
            $message = $this->messageHandler->retrieve("Data", "Found");
            $this->respondSuccess($results, $message);
        }
        else {
            $message = $this->messageHandler->retrieve("Data", "NotFound");
            $this->respondError("No companies matching {$postcode} found.", $message);
        }
    }
}

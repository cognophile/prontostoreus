<?php

namespace LocationComponent\Controller;

use Prontostoreus\Api\Controller\CycleController;

class ComponentController extends CycleController
{
    public function initialize()
    {
        $this->loadModel('Companies');
        parent::initialize();
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
        $results = $this->Companies->searchByPostcode($postcode);

        if (!empty($results)) {
            $message = $this->messageHandler->retrieve("Data", "Found");
            $this->respondSuccess($results, $message);
        }
        else {
            $message = $this->messageHandler->retrieve("Data", "NotFound");
            $this->respondError("No companies matching {$postcode} found.", $message);
        }

        // $this->setPaginateOrder(['Location.postcode' => 'asc']);

    }
}

<?php

namespace LocationComponent\Controller;

use Cake\Log\Log;
use Cake\Core\Configure;

use Prontostoreus\Api\Controller\AbstractApiController;

class CompaniesController extends AbstractApiController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('LocationComponent.Companies');
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], "Location Base: {$message}", Configure::read('Api.Routes.Locations'));
    }

    public function locate(string $postcode)
    {
        // Not a foolproof criteria as UK postcodes vary greatly, but, covers the basic variations
        if (!preg_match('/^[A-Z]{1,2}\d{1,2}(?:(-)\d[A-Z]{2})?$/', $postcode)) {
            $this->respondError($this->messageHandler->retrieve('Error', 'InvalidArgument'), 
                'The postcode must conform to the following format: AreaDistrict-SectorUnit');
            return;
        }
        
        $results = $this->Companies->find('byPostcode', ['postcode' => $postcode]);
        
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

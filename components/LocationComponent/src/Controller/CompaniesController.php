<?php

namespace LocationComponent\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\MethodNotAllowedException;

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
        $this->respondSuccess([], 200, "Location Base: {$message}", Configure::read('Api.Routes.Locations'));
    }

    public function locate(string $postcode)
    {
        $results = [];
        
        try {
            $this->requestFailWhenNot('GET');
        }
        catch (MethodNotAllowedException $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "MethodNotAllowed"));
            return;
        }

        // Not a foolproof criteria as UK postcodes vary greatly, but, covers the basic variations
        if (!preg_match('/^[A-Z]{1,2}\d{1,2}(?:(-)\d[A-Z]{2})?$/', $postcode)) {
            $this->respondError($this->messageHandler->retrieve('Error', 'InvalidArgument'), 400, 
                'The postcode must conform to the following format: AreaDistrict-SectorUnit');
            return;
        }
        
        try {
            $results = $this->Companies->find('byPostcode', ['postcode' => $postcode]);
        }
        catch (\Exception $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "Unknown"), 500);
            return;
        }
        
        if (!empty($results)) {
            $message = $this->messageHandler->retrieve("Data", "Found");
            $this->respondSuccess($results, 200, $message);
        }
        else {
            $message = $this->messageHandler->retrieve("Data", "NotFound");
            $this->respondError("No companies matching {$postcode} found.", 404, $message);
        }                                                  
    }
}

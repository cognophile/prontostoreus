<?php
namespace ApplicationComponent\Controller;

use Cake\Http\Exception\MethodNotAllowedException;

use Prontostoreus\Api\Controller\AbstractApiController;

/**
 * CompanyFurnishingRatesController Controller
 */
class CompanyFurnishingRatesController extends AbstractApiController
{
    public function initialize() 
    {
        parent::initialize();        
        $this->loadModel('ApplicationComponent.CompanyFurnishingRates');
    }

    public function fetchPriceByItem($companyId, $furnishingId) 
    {
        try {
            $this->requestFailWhenNot('GET');
        }
        catch (MethodNotAllowedException $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "MethodNotAllowed"));
            return;
        }
        
        $results = $this->CompanyFurnishingRates->find('companyItemPrice', 
            ['companyId' => $companyId, 'furnishingId' => $furnishingId]);

        $this->respondSuccess($results, 200, $this->messageHandler->retrieve("Data", "Found"));
    }
}

<?php
namespace ApplicationComponent\Controller;

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
        $results = $this->CompanyFurnishingRates->find('companyItemPrice', 
            ['companyId' => $companyId, 'furnishingId' => $furnishingId]);

        $this->respondSuccess($results, $this->messageHandler->retrieve("Data", "Found"));
        $this->response = $this->response->withStatus(200);
    }
}

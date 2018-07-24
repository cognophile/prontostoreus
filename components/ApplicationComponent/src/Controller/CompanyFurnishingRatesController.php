<?php
namespace ApplicationComponent\Controller;

use Cake\Log\Log;
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
            Log::write('error', $ex);
            $this->response = $this->response->withStatus(405);
            $this->respondError($ex->getMessage(), $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
            return;
        }
        
        $results = $this->CompanyFurnishingRates->find('companyItemPrice', 
            ['companyId' => $companyId, 'furnishingId' => $furnishingId]);

        $this->response = $this->response->withStatus(200);
        $this->respondSuccess($results, $this->messageHandler->retrieve("Data", "Found"));
    }
}

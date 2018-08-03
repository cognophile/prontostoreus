<?php
namespace ApplicationComponent\Controller;

use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\BadRequestException;

use Prontostoreus\Api\Controller\AbstractApiController;
use ApplicationComponent\Utility\TypeChecker\TypeChecker;

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
        $results = [];

        try {
            $this->requestFailWhenNot('GET');
        }
        catch (MethodNotAllowedException $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "MethodNotAllowed"));
            return;
        }

        if (!$companyId || !TypeChecker::isNumeric($companyId) || !$furnishingId || !TypeChecker::isNumeric($furnishingId)) {
            try {
                throw new BadRequestException('Both valid company and furnishing IDs must be provided');
            }
            catch (BadRequestException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }
        
        try {
            $results = $this->CompanyFurnishingRates->find('companyItemPrice', 
                ['companyId' => $companyId, 'furnishingId' => $furnishingId])->toArray();
        }
        catch (\Exception $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "Unknown"), 500);
            return;
        }

        if (!$results) {
            $this->respondError('Requested company or furnishing ID does not exist', 404, 
                $this->messageHandler->retrieve("Data", "NotFound"));
            return;
        }

        $this->respondSuccess($results, 200, $this->messageHandler->retrieve("Data", "Found"));
    }
}

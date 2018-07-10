<?php

namespace InvoiceComponent\Controller;

use Cake\Core\Configure;

use Prontostoreus\Api\Controller\AbstractApiController;

class InvoiceController extends AbstractApiController
{
    public function initialize() 
    {
        parent::initialize();  
        // TODO: Test removal of these      
        $this->loadModel('InvoiceComponent.Invoices');
        $this->loadModel('InvoiceComponent.Applications');
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], "Invoice base: {$message}", Configure::read('Api.Routes.Invoices'));
    }

    public function getApplicationCustomer($applicationId)
    {
        if (!$applicationId) {
            try {
                throw new InvalidArgumentException('An application ID must be provided.');
            }
            catch (InvalidArgumentException $ex) {
                Log::write('error', $ex->getErrors());
                $this->response = $this->response->withStatus(400);
                $this->respondError($ex->getErrors(), $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $results = $this->Applications->find('customerByApplicationId', ['applicationId' => $applicationId])
            ->toArray();
        
        $this->response = $this->response->withStatus(200);
        $this->respondSuccess($results, $this->messageHandler->retrieve("Data", "Found"));
    }

    public function getApplicationCompany($applicationId)
    {
        if (!$applicationId) {
            try {
                throw new InvalidArgumentException('An application ID must be provided.');
            }
            catch (InvalidArgumentException $ex) {
                Log::write('error', $ex->getErrors());
                $this->response = $this->response->withStatus(400);
                $this->respondError($ex->getErrors(), $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $results = $this->Applications->find('companyByApplicationId', ['applicationId' => $applicationId])
            ->toArray();
        
        $this->response = $this->response->withStatus(200);
        $this->respondSuccess($results, $this->messageHandler->retrieve("Data", "Found"));
    }

    public function getApplicationMetadata($applicationId)
    {
        if (!$applicationId) {
            try {
                throw new InvalidArgumentException('An application ID must be provided.');
            }
            catch (InvalidArgumentException $ex) {
                Log::write('error', $ex->getErrors());
                $this->response = $this->response->withStatus(400);
                $this->respondError($ex->getErrors(), $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $results = $this->Invoices->find('byApplicationId', ['applicationId' => $applicationId])->toArray();

        $this->response = $this->response->withStatus(200);
        $this->respondSuccess($results, $this->messageHandler->retrieve("Data", "Found"));
    }

    public function getApplicaitonLines($applicationId)
    {

    }

    public function produceInvoice()
    {

    }
}

<?php

namespace InvoiceComponent\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\MethodNotAllowedException;

use Prontostoreus\Api\Controller\AbstractApiController;
use InvoiceComponent\Utility\Pdf\PdfCreator;
use InvoiceComponent\Utility\TypeChecker\TypeChecker;

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
        $this->respondSuccess([], 200, "Invoice base: {$message}", Configure::read('Api.Routes.Invoices'));
    }

    public function getApplicationCustomer($applicationId)
    {
        try {
            $this->requestFailWhenNot('GET');
        }
        catch (MethodNotAllowedException $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "MethodNotAllowed"));
            return;
        }
        
        if (!$applicationId || !TypeChecker::isNumeric($applicationId)) {
            try {
                throw new BadRequestException('A valid application ID must be provided');
            }
            catch (BadRequestException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $results = $this->Applications->find('customerByApplicationId', ['applicationId' => $applicationId])
            ->toArray();

        if (!$results) {
            $this->respondError('Requested customer has no application', 404, 
                $this->messageHandler->retrieve("Data", "NotFound"));
            return;
        }
        
        $this->respondSuccess($results, 200, $this->messageHandler->retrieve("Data", "Found"));
    }

    public function getApplicationCompany($applicationId)
    {
        try {
            $this->requestFailWhenNot('GET');
        }
        catch (MethodNotAllowedException $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "MethodNotAllowed"));
            return;
        }

        if (!$applicationId || !TypeChecker::isNumeric($applicationId)) {
            try {
                throw new BadRequestException('A valid application ID must be provided');
            }
            catch (BadRequestException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $results = $this->Applications->find('companyByApplicationId', ['applicationId' => $applicationId])
            ->toArray();

        if (!$results) {
            $this->respondError('Requested application has no company', 404, 
                $this->messageHandler->retrieve("Data", "NotFound"));
            return;
        }
        
        $this->respondSuccess($results, 200, $this->messageHandler->retrieve("Data", "Found")); 
    }

    public function getInvoiceDataByApplication($applicationId)
    {
        try {
            $this->requestFailWhenNot('GET');
        }
        catch (MethodNotAllowedException $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "MethodNotAllowed"));
            return;
        }
        
        if (!$applicationId || !TypeChecker::isNumeric($applicationId)) {
            try {
                throw new BadRequestException('A valid application ID must be provided');
            }
            catch (BadRequestException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $results = $this->Invoices->find('byApplicationId', ['applicationId' => $applicationId])
            ->toArray();

        if (!$results) {
            $this->respondError('Requested application has no invoice', 404, 
                $this->messageHandler->retrieve("Data", "NotFound"));
            return;
        }

        $this->respondSuccess($results, 200, $this->messageHandler->retrieve("Data", "Found"));
    }

    public function getApplicationLines($applicationId)
    {
        try {
            $this->requestFailWhenNot('GET');
        }
        catch (MethodNotAllowedException $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "MethodNotAllowed"));
            return;
        }

        if (!$applicationId || !TypeChecker::isNumeric($applicationId)) {
            try {
                throw new BadRequestException('A valid application ID must be provided');
            }
            catch (BadRequestException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $results = $this->Applications->find('linesByApplicationId', ['applicationId' => $applicationId])
            ->toArray();

        if (!$results) {
            $this->respondError('Requested application has no lines', 404, 
                $this->messageHandler->retrieve("Data", "NotFound"));
            return;
        }

        $this->respondSuccess($results, 200, $this->messageHandler->retrieve("Data", "Found"));       
    }

    public function produceInvoice($applicationId)
    {
        try {
            $this->requestFailWhenNot('GET');
        }
        catch (MethodNotAllowedException $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "MethodNotAllowed"));
            return;
        }

        if (!$applicationId || !TypeChecker::isNumeric($applicationId)) {
            try {
                throw new BadRequestException('A valid application ID must be provided');
            }
            catch (BadRequestException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $results = $this->Invoices->find('fullApplicationInvoiceData', ['applicationId' => $applicationId])
            ->toArray();

        if (!$results) {
            $this->respondError('Requested application has no data', 404, 
                $this->messageHandler->retrieve("File", "NotRetrieved"));
            return;
        }

        $this->set(['data' => $results[0]]);
        
        $filename = 'prontostoreus_invoice_' . $results[0]['reference'] . '.pdf';
        $outputLocation = COMPS . DS . 'InvoiceComponent' . DS . 'tmp' . DS . $filename;
        
        $pdfCreator = new PdfCreator($outputLocation);
        $pdfCreator->setTemplates('InvoiceComponent.invoice', 'InvoiceComponent.default');
        $pdfCreator->setContents($this->viewVars);
        $isCreated = $pdfCreator->render();

        if (!$isCreated) {
            $this->respondError('Could not retrieve Invoice PDF for application', 500, 
                $this->messageHandler->retrieve("File", "NotRetrieved"));
            return;
        } 

        // Required as to not attempt to render the file response as a view file, but as binary
        $this->respondFile($outputLocation, $filename, true);
        return $this->response; 
    }
}

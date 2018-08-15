<?php

namespace InvoiceComponent\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Datasource\Exception\RecordNotFoundException;

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

    public function add($applicationId)
    {
        try {
            $this->requestFailWhenNot('POST');
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

        $application = $this->Applications->find('customerByApplicationId', ['applicationId' => $applicationId])->toArray();
        
        if (!$application) {
            try {
                throw new RecordNotFoundException("An Application matching the given ID was not found");
            }
            catch (RecordNotFoundException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Data", "NotFound"));
                return;
            }
        }

        $customerFirstname = $application[0]['customer']['firstname']; 
        $customerSurname = $application[0]['customer']['surname'];
        
        $invoiceData = $this->Invoices->buildInvoiceData($application[0], $customerFirstname, $customerSurname);
        $invoiceExists = $this->Invoices->find('byApplicationId', ['applicationId' => $invoiceData['application_id']])->toArray();

        if (!$invoiceExists) {
            $invoice = $this->Invoices->newEntity($invoiceData);
            $newInvoice = $this->Invoices->saveEntity($this->Invoices, $invoice);

            if ($newInvoice->getErrors()) {
                $this->respondError($newInvoice->getErrors(), 400, $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
                return;
            }
    
            return $this->respondSuccess($newInvoice, 201, $this->messageHandler->retrieve("Data", "Added"));
        }
        else {
            $invoiceId = $invoiceExists[0]['id'];
            $invoiceRecord = $this->Invoices->get($invoiceId);
            $patchedInvoice = $this->Invoices->patchEntity($invoiceRecord, $invoiceData);

            if ($patchedInvoice->getErrors()) {
                $this->respondError($patchedInvoice->getErrors(), 400, $this->messageHandler->retrieve("Error", "UnsuccessfulEdit"));
                return;
            }

            $updatedInvoice = $this->Invoices->saveEntity($this->Invoices, $patchedInvoice);

            if ($updatedInvoice->getErrors()) {
                $this->respondError($updatedInvoice->getErrors(), 400, $this->messageHandler->retrieve("Data", "NotEdited"));
                return;
            }    

            $updatedInvoice = $this->Invoices->get($invoiceId);

            $this->respondSuccess($updatedInvoice, 200, $this->messageHandler->retrieve("Data", "Edited"));
            return;
        }
    }

    public function getApplicationCustomer($applicationId)
    {
        $results = [];

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

        try {
            $results = $this->Applications->find('customerByApplicationId', ['applicationId' => $applicationId])
                ->toArray();
        }
        catch (\Exception $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "Unknown"), 500);
            return;
        }

        if (!$results) {
            $this->respondError('Requested customer has no application', 404, 
                $this->messageHandler->retrieve("Data", "NotFound"));
            return;
        }
        
        $this->respondSuccess($results, 200, $this->messageHandler->retrieve("Data", "Found"));
    }

    public function getApplicationCompany($applicationId)
    {
        $results = [];

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

        try {
            $results = $this->Applications->find('companyByApplicationId', ['applicationId' => $applicationId])
                ->toArray();
        }
        catch (\Exception $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "Unknown"), 500);
            return;
        }

        if (!$results) {
            $this->respondError('Requested application has no company', 404, 
                $this->messageHandler->retrieve("Data", "NotFound"));
            return;
        }
        
        $this->respondSuccess($results, 200, $this->messageHandler->retrieve("Data", "Found")); 
    }

    public function getInvoiceDataByApplication($applicationId)
    {
        $results = [];

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

        try {
            $results = $this->Invoices->find('byApplicationId', ['applicationId' => $applicationId])
                ->toArray();
        }
        catch (\Exception $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "Unknown"), 500);
            return;
        }

        if (!$results) {
            $this->respondError('Requested application has no invoice', 404, 
                $this->messageHandler->retrieve("Data", "NotFound"));
            return;
        }

        $this->respondSuccess($results, 200, $this->messageHandler->retrieve("Data", "Found"));
    }

    public function getApplicationLines($applicationId)
    {
        $results = [];

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

        try {
            $results = $this->Applications->find('linesByApplicationId', ['applicationId' => $applicationId])
                ->toArray();
        }
        catch (\Exception $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "Unknown"), 500);
            return;
        }

        if (!$results) {
            $this->respondError('Requested application has no lines', 404, 
                $this->messageHandler->retrieve("Data", "NotFound"));
            return;
        }

        $this->respondSuccess($results, 200, $this->messageHandler->retrieve("Data", "Found"));       
    }

    public function produceInvoice($applicationId)
    {
        $results = [];
        
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

        try {
            $results = $this->Invoices->find('fullApplicationInvoiceData', ['applicationId' => $applicationId])
                ->toArray();
        }
        catch (\Exception $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "Unknown"), 500);
            return;
        }

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

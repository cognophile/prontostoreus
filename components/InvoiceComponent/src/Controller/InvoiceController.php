<?php

namespace InvoiceComponent\Controller;

use Cake\Core\Configure;

use Prontostoreus\Api\Controller\AbstractApiController;
use InvoiceComponent\Utility\Pdf\PdfCreator;

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
        
        if (!$applicationId) {
            try {
                throw new InvalidArgumentException('An application ID must be provided.');
            }
            catch (InvalidArgumentException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $results = $this->Applications->find('customerByApplicationId', ['applicationId' => $applicationId])
            ->toArray();
        
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

        if (!$applicationId) {
            try {
                throw new InvalidArgumentException('An application ID must be provided.');
            }
            catch (InvalidArgumentException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $results = $this->Applications->find('companyByApplicationId', ['applicationId' => $applicationId])
            ->toArray();
        
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
        
        if (!$applicationId) {
            try {
                throw new InvalidArgumentException('An application ID must be provided.');
            }
            catch (InvalidArgumentException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $results = $this->Invoices->find('byApplicationId', ['applicationId' => $applicationId])->toArray();

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

        if (!$applicationId) {
            try {
                throw new InvalidArgumentException('An application ID must be provided.');
            }
            catch (InvalidArgumentException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $results = $this->Applications->find('linesByApplicationId', ['applicationId' => $applicationId])->toArray();

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

        if (!$applicationId) {
            try {
                throw new InvalidArgumentException('An application ID must be provided.');
            }
            catch (InvalidArgumentException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $data = $this->Invoices->find('fullApplicationInvoiceData', ['applicationId' => $applicationId])->toArray();
        $this->set(['data' => $data[0]]);
        
        $filename = 'prontostoreus_invoice_' . $data[0]['reference'] . '.pdf';
        $outputLocation = COMPS . DS . 'InvoiceComponent' . DS . 'tmp' . DS . $filename;
        
        $pdfCreator = new PdfCreator($outputLocation);
        $pdfCreator->setTemplates('InvoiceComponent.invoice', 'InvoiceComponent.default');
        $pdfCreator->setContents($this->viewVars);
        $isCreated = $pdfCreator->render();

        if (!$isCreated) {
            $this->respondError('Could not retrieve Invoice PDF: ' . $outputLocation, 500, 
                $this->messageHandler->retrieve("File", "NotRetrieved"));
            return;
        } 

        // Required as to not attempt to render the file response as a view file, but as binary
        $this->respondFile($outputLocation, $filename, true);
        return $this->response; 
    }
}

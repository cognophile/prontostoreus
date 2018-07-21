<?php

namespace InvoiceComponent\Controller;

use Cake\Core\Configure;

use Prontostoreus\Api\Controller\AbstractApiController;
use InvoiceComponent\Utility\Pdf;

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
        if ($this->request->is('GET')) {
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
        else {
            throw new MethodNotAllowedException("HTTP Method disabled for creation: Use GET");
        }
    }

    public function getApplicationCompany($applicationId)
    {
        if ($this->request->is('GET')) {
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
        else {
            throw new MethodNotAllowedException("HTTP Method disabled for creation: Use GET");
        }
    }

    public function getInvoiceDataByApplication($applicationId)
    {
        if ($this->request->is('GET')) {
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
        else {
            throw new MethodNotAllowedException("HTTP Method disabled for creation: Use GET");
        }
    }

    public function getApplicationLines($applicationId)
    {
        if ($this->request->is('GET')) {
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
    
            $results = $this->Applications->find('linesByApplicationId', ['applicationId' => $applicationId])->toArray();
    
            $this->response = $this->response->withStatus(200);
            $this->respondSuccess($results, $this->messageHandler->retrieve("Data", "Found"));  
        }
        else {
            throw new MethodNotAllowedException("HTTP Method disabled for creation: Use GET");
        }      
    }

    public function produceInvoice($applicationId)
    {
        if ($this->request->is('GET')) {
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

            $data = $this->Invoices->find('fullApplicationInvoiceData', ['applicationId' => $applicationId])->toArray();
            $this->set(['data' => $data[0]]);
            
            $filename = 'prontostoreus_invoice_' . $data[0]['reference'] . '.pdf';
            $outputLocation = COMPS . DS . 'InvoiceComponent' . DS . 'tmp' . DS . $filename;
            
            $pdfCreator = new PdfCreator($outputLocation);
            $pdfCreator->setTemplates('InvoiceComponent.invoice', 'InvoiceComponent.default');
            $pdfCreator->setContents($this->viewVars);
            $isCreated = $pdfCreator->render();

            if (!$isCreated) {
                Log::write('error', 'Could not retrieve Invoice PDF: ' . $outputLocation);
                $this->response = $this->response->withStatus(400);
                $this->respondError('Could not retrieve Invoice PDF: ' . $outputLocation, $this->messageHandler->retrieve("File", "NotRetrieved"));
                return;
            } 

            $this->response = $this->response->withStatus(201);
            $this->response = $this->response->withFile($outputLocation, ['name' => $filename, 'download' => true]);
            
            // Required as to not attempt to render the file response as a view
            return $this->response;
        }
        else {
            throw new MethodNotAllowedException("HTTP Method disabled for creation: Use GET");
        } 
    }
}

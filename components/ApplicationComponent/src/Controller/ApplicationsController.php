<?php
namespace ApplicationComponent\Controller;

use Cake\Log\Log;
use Cake\Core\Configure;

use Prontostoreus\Api\Controller\AbstractApiController;

/**
 * Applications Controller
 */
class ApplicationsController extends AbstractApiController
{
    public function initialize() 
    {
        parent::initialize();        
        $this->loadModel('ApplicationComponent.Applications');
        $this->loadModel('InvoiceComponent.Invoices');
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], "Application base: {$message}", Configure::read('Api.Routes.Applications'));
    }

    public function add() 
    {
        return $this->universalAdd($this->Applications);
    }

    public function edit($applicationId) 
    {
        if (!$applicationId) {
            try {
                throw new InvalidArgumentException('An application ID must be provided.');
            }
            catch (InvalidArgumentException $ex) {
                Log::write('error', $ex->getErrors());
                $this->response = $this->response->withStatus(400);
                $this->respondError($ex->getErrors(), $this->messageHandler->retrieve("Error", "InvalidArgument"));
            }
        }

        $isSuccessful = $this->createInvoice($this->request->getData());
        return $this->universalEdit($this->Applications, $applicationId);
    }

    private function createInvoice(array $applicationData)
    {
        if (!is_array($applicationData) || empty($applicationData)) {
            throw new InvalidArgumentException('The provided data must be a valid array.');
        }

        $customer = $this->Applications->find('customerByApplicationId')->toArray();
        $invoiceData = $this->Invoices->buildInvoiceData($applicationData, $customer['firstname'], $customer['surname']);
        
        $invoice = $this->Invoices->newEntity($invoiceData);
        $newInvoice = $this->Invoices->saveEntity($this->Invoices, $invoice);

        if ($newInvoice->getErrors()) {
            Log::write('error', $entity->getErrors());
            return false;
        }

        return true;
    }
}

<?php
namespace ApplicationComponent\Controller;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Datasource\Exception\RecordNotFoundException;

use Prontostoreus\Api\Controller\AbstractApiController;

/**
 * Applications Controller
 */
class ApplicationsController extends AbstractApiController
{
    public function initialize() 
    {
        parent::initialize();        
        $this->InvoiceApplications = TableRegistry::get('InvoiceComponent.Applications');
        $this->loadModel('InvoiceComponent.Invoices');
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], 200, "Application base: {$message}", Configure::read('Api.Routes.Applications'));
    }

    public function add() 
    {
        $data = $this->request->getData();

        if (empty($data['application_lines'])) {
            $this->respondError('Cannot create an application without furniture lines data', 422,
                $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
            return;
        }

        return $this->universalAdd($this->Applications);
    }

    public function edit($applicationId) 
    {
        if (!$applicationId) {
            try {
                throw new InvalidArgumentException('An application ID must be provided.');
            }
            catch (InvalidArgumentException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        try {
            $isSuccessful = $this->createInvoice($this->request->getData());
        }
        catch (Excetion $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "Unknown"));
            return;
        }
        
        return $this->universalEdit($this->Applications, $applicationId);
    }

    private function createInvoice(array $applicationData)
    {
        // TODO: Add test for this (document only happens on re-post of full application to edit [fix])
        if (!is_array($applicationData) || empty($applicationData)) {
            throw new InvalidArgumentException("The provided data must be a valid array.");
        }

        $application = $this->InvoiceApplications->find('customerByApplicationId', ['applicationId' => $applicationData['id']])->toArray();
        
        if (!$application) {
            throw new RecordNotFoundException("An Application matching the given ID was not found");
        }

        $customerFirstname = $application[0]['customer']['firstname']; 
        $customerSurname = $application[0]['customer']['surname'];
        $invoiceData = $this->Invoices->buildInvoiceData($applicationData, $customerFirstname, $customerSurname);

        $invoice = $this->Invoices->newEntity($invoiceData);
        $newInvoice = $this->Invoices->saveEntity($this->Invoices, $invoice);

        if ($newInvoice->getErrors()) {
            // * Don't respond with an error as this is a hidden process
            Log::write('error', $newInvoice->getErrors());
            return false;
        }

        return true;
    }
}

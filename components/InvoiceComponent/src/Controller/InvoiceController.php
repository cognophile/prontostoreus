<?php

namespace InvoiceComponent\Controller;

use Cake\Core\Configure;

use Prontostoreus\Api\Controller\AbstractApiController;

class InvoiceController extends AbstractApiController
{
    public function initialize() 
    {
        parent::initialize();        
        $this->loadModel('InvoiceComponent.Invoices');
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], "Invoice base: {$message}", Configure::read('Api.Routes.Invoices'));
    }
}

<?php

namespace ConfirmationComponent\Controller;

use Prontostoreus\Api\Controller\AbstractApiController;

class ConfirmationsController extends AbstractApiController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('ConfirmationsComponent.Confirmations');
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], "Confirmations Base: {$message}");
    }

    public function acceptTerms() 
    { 
        // Should only be one confirmation record for each application - strict one-to-one
        // Is put right, therefore? Create once, update forever more for that application.
        
        // ! Search for existing record for this application Id first.
        // ! If it exists, respond as such. (Update also?)
        // ! Otherwise, call the parent method to create one. 
        return parent::universalAdd($this->Confirmations, false);
    }
}

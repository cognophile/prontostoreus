<?php

namespace ConfirmationComponent\Controller;

use Prontostoreus\Api\Controller\AbstractApiController;

class ConfirmationsController extends AbstractApiController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('ConfirmationComponent.Confirmations');
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], "Confirmations Base: {$message}");
    }

    public function acceptTerms() 
    {   
        $data = $this->request->getData();

        if (!empty($data)) {
            $this->respondError("Empty payload", $this->messageHandler->retrieve("Error", "MissingPayload"));
            $this->response = $this->response->withStatus(404);
        }
        
        $applicationId = $data['application_id']; 
        
        if ($applicationId) {
            $results = $this->Confirmations->find('byApplicationId', ['application_id' => $applicationId]);

            if (!empty($results)) {
                return parent::universalEdit($this->Confirmations, $results[0]['id'], true);
            }
            else {
                return parent::universalAdd($this->Confirmations, false);
            }
        }
    }
}

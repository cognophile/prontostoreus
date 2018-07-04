<?php

namespace ConfirmationComponent\Controller;

use Cake\Core\Configure;

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
        $this->respondSuccess([], "Confirmations Base: {$message}", Configure::read('Api.Routes.Confirmations'));
    }

    public function acceptTerms() 
    {   
        $data = $this->request->getData();

        if (!empty($data)) {
            $this->response = $this->response->withStatus(404);
            $this->respondError("Empty payload", $this->messageHandler->retrieve("Error", "MissingPayload"));
        }
        
        $applicationId = $data['application_id']; 
        
        if ($applicationId) {
            $results = $this->Confirmations->find('byApplicationId', ['application_id' => $applicationId]);

            if (!empty($results)) {
                return $this->universalEdit($this->Confirmations, $results[0]['id'], false);
            }
            else {
                return $this->universalAdd($this->Confirmations, false);
            }
        }
    }
}

<?php

namespace ConfirmationComponent\Controller;

use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Http\Exception\MethodNotAllowedException;

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
        try {
            $this->requestFailWhenNot('POST');
        }
        catch (MethodNotAllowedException $ex) {
            Log::write('error', $ex);
            $this->response = $this->response->withStatus(405);
            $this->respondError($ex->getMessage(), $this->messageHandler->retrieve("Error", "UnsuccessfulEdit"));
            return;
        }

        $data = $this->request->getData();

        if (empty($data)) {
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

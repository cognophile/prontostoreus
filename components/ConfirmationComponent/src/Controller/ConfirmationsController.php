<?php

namespace ConfirmationComponent\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\BadRequestException;

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
        $this->respondSuccess([], 200, "Confirmations Base: {$message}", Configure::read('Api.Routes.Confirmations'));
    }

    public function acceptTerms() 
    {   
        try {
            $this->requestFailWhenNot('POST');
        }
        catch (MethodNotAllowedException $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "UnsuccessfulEdit"));
            return;
        }

        $data = $this->request->getData();

        if (empty($data)) {
            try {
                throw new BadRequestException();
            }
            catch (BadRequestException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "MissingPayload"));
                return;
            }
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

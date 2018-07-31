<?php

namespace ConfirmationComponent\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\BadRequestException;

use Prontostoreus\Api\Controller\AbstractApiController;
use ConfirmationComponent\Utility\TypeChecker;

class ConfirmationsController extends AbstractApiController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], 200, "Confirmations Base: {$message}", Configure::read('Api.Routes.Confirmations'));
    }

    public function acceptTerms($applicationId) 
    {   
        $this->loadModel('ConfirmationComponent.Confirmations');
        $this->loadModel('ConfirmationComponent.Applications');
    
        try {
            $this->requestFailWhenNot('POST');
        }
        catch (MethodNotAllowedException $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "UnsuccessfulEdit"));
            return;
        }
        
        if (!$applicationId || !TypeChecker::isNumeric($applicationId)) {
            try {
                throw new BadRequestException('A valid application ID must be provided');
            }
            catch (BadRequestException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
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

        if ($applicationId) {
            $results = $this->Confirmations->find('byApplicationId', ['application_id' => $applicationId])->toArray();

            if (!empty($results)) {
                return $this->universalEdit($this->Confirmations, $results[0]['id'], false);
            }
            else {
                $applicationExists = $this->Applications->find('byId', ['id' => $applicationId])->toArray();

                if (!$applicationExists) {
                    try {
                        throw new BadRequestException($this->messageHandler->retrieve("Error", "RecordNotFound"));
                    }
                    catch (BadRequestException $ex) {
                        $this->respondException($ex, $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
                        return;
                    }
                }
                
                return $this->universalAdd($this->Confirmations, false);
            }
        }
    }
}

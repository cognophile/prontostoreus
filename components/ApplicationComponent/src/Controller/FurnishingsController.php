<?php
namespace ApplicationComponent\Controller;

use Cake\Log\Log;
use Cake\Http\Exception\MethodNotAllowedException;

use Prontostoreus\Api\Controller\AbstractApiController;

/**
 * Furnishings Controller
 */
class FurnishingsController extends AbstractApiController
{
    public function initialize() 
    {
        parent::initialize();        
        $this->loadModel('ApplicationComponent.Furnishings');
    }

    public function fetchFurnishingListByRoom($roomId)
    {
        try {
            $this->requestFailWhenNot('GET');
        }
        catch (MethodNotAllowedException $ex) {
            Log::write('error', $ex);
            $this->response = $this->response->withStatus(405);
            $this->respondError($ex->getMessage(), $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
            return;
        }

        $this->loadModel('ApplicationComponent.Furnishings');
        $results = $this->Furnishings->find('byRoomId', ['roomId' => $roomId]);

        $this->response = $this->response->withStatus(200);
        $this->respondSuccess($results, $this->messageHandler->retrieve("Data", "Found"));
    }

    public function fetchFurnishingSize($roomId, $furnishingId)
    {
        $this->loadModel('ApplicationComponent.Furnishings');
        return parent::universalView($this->Furnishings, $furnishingId);
    }
}

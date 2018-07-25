<?php
namespace ApplicationComponent\Controller;

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
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "MethodNotAllowed"));
            return;
        }

        $this->loadModel('ApplicationComponent.Furnishings');
        $results = $this->Furnishings->find('byRoomId', ['roomId' => $roomId]);

        $this->respondSuccess($results, 200, $this->messageHandler->retrieve("Data", "Found"));
    }

    public function fetchFurnishingSize($roomId, $furnishingId)
    {
        $this->loadModel('ApplicationComponent.Furnishings');
        return parent::universalView($this->Furnishings, $furnishingId);
    }
}

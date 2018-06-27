<?php
namespace ApplicationComponent\Controller;

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
        $this->loadModel('ApplicationComponent.Furnishings');
        $results = $this->Furnishings->find('byRoomId', ['roomId' => $roomId]);

        $this->respondSuccess($results, $this->messageHandler->retrieve("Data", "Found"));
        $this->response = $this->response->withStatus(200);
    }

    public function fetchFurnishingSize($roomId, $furnishingId)
    {
        $this->loadModel('ApplicationComponent.Furnishings');
        return parent::universalView($this->Furnishings, $furnishingId);
    }
}

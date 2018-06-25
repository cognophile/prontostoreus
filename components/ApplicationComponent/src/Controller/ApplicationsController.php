<?php
namespace ApplicationComponent\Controller;

use Prontostoreus\Api\Controller\AbstractApiController;

/**
 * Applications Controller
 */
class ApplicationsController extends AbstractApiController
{
    public function initialize() 
    {
        parent::initialize();        
        $this->loadModel('ApplicationComponent.Applications');
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], "Application base: {$message}");
    }

    public function add() 
    {

    }

    public function fetchRoomList($roomId = null)
    {
        $this->loadModel('ApplicationComponent.Rooms');
        return parent::universalView($this->Rooms, $roomId);
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

    public function fetchFurnishingPrice($furnishingId) 
    {

    }
}

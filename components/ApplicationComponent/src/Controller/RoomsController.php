<?php
namespace ApplicationComponent\Controller;

use Prontostoreus\Api\Controller\AbstractApiController;

/**
 * Rooms Controller
 */
class RoomsController extends AbstractApiController
{
    public function initialize() 
    {
        parent::initialize();        
        $this->loadModel('ApplicationComponent.Rooms');
    }

    public function fetchRoomList($roomId = null)
    {
        $this->loadModel('ApplicationComponent.Rooms');
        return parent::universalView($this->Rooms, $roomId);
    }
}

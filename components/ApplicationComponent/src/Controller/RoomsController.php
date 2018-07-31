<?php
namespace ApplicationComponent\Controller;

use Cake\Http\Exception\BadRequestException;

use Prontostoreus\Api\Controller\AbstractApiController;
use ApplicationComponent\Utility\TypeChecker\TypeChecker;

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
        if ($roomId != null && !TypeChecker::isNumeric($roomId)) {
            try {
                throw new BadRequestException('A valid room ID must be provided');
            }
            catch (BadRequestException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }

        $this->loadModel('ApplicationComponent.Rooms');
        return parent::universalView($this->Rooms, $roomId);
    }
}

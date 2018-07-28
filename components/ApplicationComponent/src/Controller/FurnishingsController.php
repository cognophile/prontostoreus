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
        $results = $this->Furnishings->find('byRoomId', ['roomId' => $roomId])->toArray();

        if (!$results) {
            $this->respondError('Requested room does not exist', 404, 
                $this->messageHandler->retrieve("Data", "NotFound"));
            return;
        }

        $this->respondSuccess($results, 200, $this->messageHandler->retrieve("Data", "Found"));
    }

    public function fetchFurnishingSize($roomId, $furnishingId)
    {
        try {
            $this->requestFailWhenNot('GET');
        }
        catch (MethodNotAllowedException $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "MethodNotAllowed"));
            return;
        }

        $this->loadModel('ApplicationComponent.Furnishings');
        $results = $this->Furnishings->find('byRoomId', ['roomId' => $roomId])->enableHydration(false)->toArray();

        $furnishing = array_search($furnishingId, array_column($results, 'id'));

        if ($furnishing === false) {
            $this->respondError('Requested furnishing not associated with requested room', 404, 
                $this->messageHandler->retrieve("Data", "NotFound"));
            return;
        }

        return parent::universalView($this->Furnishings, $furnishingId);
    }
}

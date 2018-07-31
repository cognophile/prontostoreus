<?php
namespace ApplicationComponent\Controller;

use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\BadRequestException;

use Prontostoreus\Api\Controller\AbstractApiController;
use ApplicationComponent\Utility\TypeChecker;

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

        if (!$roomId || !TypeChecker::isNumeric($roomId)) {
            try {
                throw new BadRequestException('A valid room ID must be provided');
            }
            catch (BadRequestException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
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

        if (!$roomId || !TypeChecker::isNumeric($roomId) || !$furnishingId || !TypeChecker::isNumeric($furnishingId)) {
            try {
                throw new BadRequestException('Both valid room and furnishing IDs must be provided');
            }
            catch (BadRequestException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
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

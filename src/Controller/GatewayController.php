<?php

namespace Prontostoreus\Api\Controller;

use Cake\Event\Event;
use Cake\Filesystem\Folder;

class GatewayController extends CycleController
{
    public function baseResponse()
    {
        $response = [
            'message' => $this->messageHandler->retrieve("General", "Welcome"),
            'version' => $this->messageHandler->retrieve("General", "Version"),
            'success' => true
        ];

        $this->set($response);
    }
}

<?php

namespace Prontostoreus\Api\Controller;

use Cake\Event\Event;
use Cake\Filesystem\Folder;

class ApiController extends CycleController
{
    public function status()
    {
        $response = [
            'message' => $this->messageHandler->retrieve("General", "Welcome"),
            'version' => $this->messageHandler->retrieve("General", "Version"),
            'links' => Configure::read('Api.Routes.Location'),
            'success' => true
        ];

        $this->set($response);
    }
}

<?php

namespace Prontostoreus\Api\Controller;

use Cake\Event\Event;
use Cake\Filesystem\Folder;
use Cake\Core\Configure;

class BaseController extends AbstractApiController
{
    public function status()
    {
        $response = [
            'message' => $this->messageHandler->retrieve("General", "Welcome"),
            'version' => $this->messageHandler->retrieve("General", "Version"),
            'links' => Configure::read('Api.Routes.Locations'),
            'success' => true
        ];

        $this->set($response);
    }
}

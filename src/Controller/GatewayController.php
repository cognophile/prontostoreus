<?php

namespace Prontostoreus\Api\Controller;

use Prontostoreus\Api\Utility\MessageResponder;
use Cake\Event\Event;
use Cake\Filesystem\Folder;

class GatewayController extends CycleController
{
    public function baseResponse()
    {
        $responder = new MessageResponder(
            new Folder(dirname(dirname(__DIR__)) . "/resources/"), "responseMessages.json");

        $response = [
            'message' => $responder->getMessage("General", "Welcome"),
            'version' => $responder->getMessage("General", "Version"),
            'success' => true
        ];

        $this->set($response);
    }
}

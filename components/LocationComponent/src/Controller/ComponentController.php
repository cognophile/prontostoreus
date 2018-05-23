<?php

namespace LocationComponent\Controller;

use Prontostoreus\Api\Controller\CycleController;

class ComponentController extends CycleController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "Welcome");
        $this->respondSuccess([], "Location Component: " . $message);
    }
}

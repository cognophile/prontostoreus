<?php

namespace CustomerDetailsComponent\Controller;

use Prontostoreus\Api\Controller\CycleController;

class ComponentController extends CycleController
{
    public function initialize() 
    {
        parent::initialize();
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], "Customer details base: {$message}");
    }
}

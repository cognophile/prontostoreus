<?php

namespace Prontostoreus\Api\Controller;

use Cake\Event\Event;

class GatewayController extends CycleController
{
    public function baseResponse()
    {
        $response = [
            'message' => 'Prontostoreus API - Welcome!',
            'version' => '0.1.0',
            'success' => true
        ];

        $this->set($response);
    }
}

<?php
namespace ApplicationComponent\Controller;

use Prontostoreus\Api\Controller\AbstractApiController;

/**
 * Applications Controller
 */
class ApplicationsController extends AbstractApiController
{
    public function initialize() 
    {
        parent::initialize();        
        $this->loadModel('ApplicationComponent.Applications');
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], "Application base: {$message}");
    }
}

<?php
namespace ApplicationComponent\Controller;

use Cake\Log\Log;
use Cake\Core\Configure;

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
        $this->respondSuccess([], "Application base: {$message}", Configure::read('Api.Routes.Applications'));
    }

    public function add() 
    {
        return $this->universalAdd($this->Applications);
    }

    public function edit($applicationId) 
    {
        return $this->universalEdit($this->Applications, $applicationId);
    }
}

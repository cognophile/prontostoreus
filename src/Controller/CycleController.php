<?php

namespace Prontostoreus\Api\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Prontostoreus\Api\Utility\MessageHandler;

class CycleController extends Controller
{
    use CycleHydrationTrait;

    protected $messageHandler;

    /**
     * Initialization hook method for common initialization code like loading components.
     * 
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Paginator');

        $this->messageHandler = new MessageHandler(
            new Folder(dirname(dirname(__DIR__)) . Configure::read('Folder.Resources')), 
            Configure::read('File.ResponseMessages')
        );

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    /**
     * Add a record of the given Entity type
     * 
     * @param mixed $entity
     * @return void
     */
    protected function add() 
    {
        /* Here for future ref of response rendering wiring
            $isAvailable = true;

            if ($isAvailable) {
                $data = ["id" => "1", "username" => "Bob", "email" => "example@domain.com"];
                $message = (!empty($data)) ? $this->messageHandler->retrieve("Data", "Found") : 
                    $this->messageHandler->retrieve("Data", "NotFound");

                $this->respondSuccess($data, $message);
            }
            else {
                $error = "UnrecognisedCredentials: (Mock) Message and Stack Trace Go Here.";
                $message = (!empty($error)) ? $this->messageHandler->retrieve("Error", strtok($error, ":")) : 
                    $this->messageHandler->retrieve("Data", "Unknown");


                $this->respondError($error, $message);
            }
        */        
    }

    protected function view() 
    {
    }

    protected function edit() 
    {
    }

    protected function remove() 
    {
    }
}

<?php

namespace Prontostoreus\Api\Controller;

use Cake\Controller\Controller;
use Cake\Http\Exception;
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
    protected function universalAdd($entity) 
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (!empty($data)) {
                $newEntity = $entity->newEntity($data);

                if ($entity->save($newEntity)) {
                    $newData = $entity->get($newEntity->id);
                    $newId = $newData->id;
                    $this->respondSuccess([$newId], $this->messageHandler->retrieve("Data", "Added"));
                    $this->response = $this->response->withStatus(201);
                } else {
                    $this->respondError([$newEntity->errors()], $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
                    $this->response = $this->response->withStatus(400);
                }
            }
            else {
                try {
                    throw new BadRequestException($this->messageHandler->retrieve("Error", "MissingPayload"));
                }
                catch (Exception $ex) {                   
                    $this->respondError($ex->getTrace(), $ex->getMessage());
                    $this->response = $this->response->withStatus(422);
                }
            }
        } else {
            throw new \MethodNotAllowedException("HTTP Method disabled for creation: Use POST");
        }        
    }

    protected function universalView($id = null) 
    {
    }

    protected function universalEdit() 
    {
    }

    protected function universalRemove() 
    {
    }
}

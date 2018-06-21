<?php

namespace Prontostoreus\Api\Controller;

use Cake\Controller\Controller as CakeController;
use Cake\Http\Exception as HttpException;
use Cake\Network\Exception as NetworkException;
use Cake\Datasource\Exception as DataException;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;

use Prontostoreus\Api\Utility\MessageHandler;

abstract class AbstractApiController extends CakeController
{
    use ApiHydrationTrait;

    protected $messageHandler;
    protected $associated = [];
    protected $contained = [];

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
        // $this->loadComponent('Security');
        // $this->loadComponent('Csrf');
    }

    /**
     * Add a record of the given Entity type, with an optional association
     * 
     * @param \Cake\ORM\Table $entity The entity model to store
     * @param boolean $hasRelated Flag to determine whether we look for and store related records
     * @throws Cake\Http\Exception\BadRequestException
     * @throws Cake\Http\Exception\MethodNotAllowedException
     * @return void
     */
    protected function universalAdd(\Cake\ORM\Table $entityModel, bool $hasAssociations = false) 
    {
        if ($this->request->is('post')) {        
            $data = $this->request->getData();

            if (!empty($data)) {
                $associated = $entityModel->getAssociations(0);
                $newEntity = $entityModel->newEntity($data);

                if (!empty($associated)) {
                    $newEntity = $entityModel->newEntity($data, ['associated' => $associated]);
                }

                $entity = $entityModel->saveEntity($entityModel, $newEntity);

                if ($entity->getErrors()) {
                    $this->respondError($entity->getErrors(), $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
                    $this->response = $this->response->withStatus(400);
                }

                if (!empty($entity) && $hasAssociations) {
                    $associatedEntity = $entityModel->saveAssociatedEntity($entityModel, $entity, $data);

                    if ($associatedEntity->getErrors()) {
                        $this->respondError($associatedEntity->getErrors(), $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
                        $this->response = $this->response->withStatus(400);
                    }
                } 

                $contained = $entityModel->getContained(0);
                $created = $entityModel->get($newEntity->id);
                if (!empty($contained)) {
                    $created = $entityModel->get($newEntity->id, ['contain' => $contained]);
                }
                
                $this->respondSuccess($created, $this->messageHandler->retrieve("Data", "Added"));
                $this->response = $this->response->withStatus(201);
            }
            else {
                throw new BadRequestException($this->messageHandler->retrieve("Error", "MissingPayload"));
            }
        } else {
            throw new MethodNotAllowedException("HTTP Method disabled for creation: Use POST");
        } 
    }

    protected function universalView($id = null) 
    {

    }

    protected function universalEdit(\Cake\ORM\Table $entityModel, $recordId, $skipMethodCheck = false) 
    {

        if ($this->request->is('put') || $skipMethodCheck) {
            $data = $this->request->getData();

            if (!empty($data) && !empty($recordId)) {
                try {
                    $contained = $entityModel->getContained(0);
                    $recordModel = $entityModel->get($recordId);

                    if (!empty($contained)) {
                        $recordModel = $entityModel->get($recordId, ['contain' => $contained]);
                    }

                    $associated = $entityModel->getAssociations(0);
                    $patchedEntity = $entityModel->patchEntity($recordModel, $data);

                    if (!empty($associated)) {
                        $patchedEntity = $entityModel->patchEntity($recordModel, $data, ['associated' => $associated]);
                    }
                       
                    $updatedEntity = $entityModel->saveEntity($entityModel, $patchedEntity);

                    if ($updatedEntity) {
                        $updatedRecord = $entityModel->get($recordId);

                        if ($contained) {
                            $updatedRecord = $entityModel->get($recordId, ['contain' => $contained]);
                        }

                        $this->respondSuccess($updatedRecord, $this->messageHandler->retrieve("Data", "Edited"));
                        $this->response = $this->response->withStatus(201);
                    } else {
                        $this->respondError($updatedEntity->getErrors(), $this->messageHandler->retrieve("Data", "NotEdited"));
                        $this->response = $this->response->withStatus(400);
                    }
                } catch (RecordNotFoundException $ex) {
                    $this->respondError($ex->getMessage(), $this->messageHandler->retrieve("Data", "NotFound"));
                    $this->response = $this->response->withStatus(404);
                }
            }
            else {
                throw new BadRequestException($this->messageHandler->retrieve("Error", "MissingPayload"));
            }
        } else {
            throw new MethodNotAllowedException("HTTP Method disabled for creation: Use PUT or PATCH");
        } 


    }

    protected function universalRemove() 
    {
    }
}
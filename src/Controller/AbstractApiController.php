<?php

namespace Prontostoreus\Api\Controller;

use Cake\Controller\Controller as CakeController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Log\Log;

use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Datasource\Exception\RecordNotFoundException;

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
                    // TODO: Configure error handler and renderer and logging, centrally
                    Log::write('error', $entity->getErrors());
                    $this->respondError($entity->getErrors(), $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
                    $this->response = $this->response->withStatus(400);
                }

                if (!empty($entity) && $hasAssociations) {
                    $associatedEntity = $entityModel->saveAssociatedEntity($entityModel, $entity, $data);

                    if ($associatedEntity->getErrors()) {
                        Log::write('error', $entity->getErrors());
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

    /**
     * View a record of the given Entity type, with an optional record ID 
     * 
     * @param \Cake\ORM\Table $entity The entity model to view
     * @param integer $recordId Optional specification to view a single record. If null, gets all.
     * @throws Cake\Http\Exception\MethodNotAllowedException
     * @return void
     */
    protected function universalView(\Cake\ORM\Table $entityModel, int $recordId = null) 
    {
        if ($this->request->is('get')) {
            if (empty($recordId)) {
                $results = $entityModel->getAll();

                if ($results instanceof RecordNotFoundException) {
                    Log::write('error', $results->getMessage());
                    $this->respondError($results->getMessage(), $this->messageHandler->retrieve("Data", "NotFound"));
                    $this->response = $this->response->withStatus(405);
                }

                $this->respondSuccess($results, $this->messageHandler->retrieve("Data", "Found"));
                $this->response = $this->response->withStatus(200);
            }
            else {
                $result = $entityModel->getOne($recordId);

                if ($result instanceof RecordNotFoundException) {
                    Log::write('error', $results->getMessage());
                    $this->respondError($result->getMessage(), $this->messageHandler->retrieve("Data", "NotFound"));
                    $this->response = $this->response->withStatus(405);
                }

                $this->respondSuccess($result, $this->messageHandler->retrieve("Data", "Found"));
                $this->response = $this->response->withStatus(200);
            }
        }
        else {
            throw new MethodNotAllowedException("HTTP Method disabled for creation: Use POST");
        }
    }

    /**
     * Edit a record of the given Entity type and record ID, with an optional association.
     * 
     * @param \Cake\ORM\Table $entity The entity model to edit
     * @param integer $recordId ID of the record to edit
     * @throws Cake\Datasource\Exception\RecordNotFoundException
     * @throws Cake\Http\Exception\BadRequestException
     * @throws Cake\Http\Exception\MethodNotAllowedException
     * @return void
     */
    protected function universalEdit(\Cake\ORM\Table $entityModel, $recordId) 
    {
        if ($this->request->is('put') || $this->request->is('post')) {
            $data = $this->request->getData();

            if (!empty($data) && !empty($recordId)) {
                try {
                    $contained = $entityModel->getContained(0);
                    $recordModel = $entityModel->get($recordId);

                    if (!empty($contained)) {
                        $recordModel = $entityModel->get($recordId, ['contain' => [$contained]]);
                    }

                    $associated = $entityModel->getAssociations(0);
                    $patchedEntity = $entityModel->patchEntity($recordModel, $data);

                    if (!empty($associated)) {
                        $patchedEntity = $entityModel->patchEntity($recordModel, $data, ['associated' => [$associated]]);
                    }
                       
                    $updatedEntity = $entityModel->saveEntity($entityModel, $patchedEntity);

                    if ($updatedEntity) {
                        $updatedRecord = $entityModel->get($recordId);

                        if ($contained) {
                            $updatedRecord = $entityModel->get($recordId, ['contain' => $contained]);
                        }

                        $this->respondSuccess($updatedRecord, $this->messageHandler->retrieve("Data", "Edited"));
                        $this->response = $this->response->withStatus(200);
                    } else {
                        Log::write('error', $updatedEntity->getErrors());
                        $this->respondError($updatedEntity->getErrors(), $this->messageHandler->retrieve("Data", "NotEdited"));
                        $this->response = $this->response->withStatus(400);
                    }
                } catch (RecordNotFoundException $ex) {
                    Log::write('error', $results->getMessage());
                    $this->respondError($ex->getMessage(), $this->messageHandler->retrieve("Data", "NotFound"));
                    $this->response = $this->response->withStatus(405);
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
        if ($this->request->is('delete')) {
                $recordEntity = $entityModel->getOne($recordId);
                $result = $entityModel->delete($recordEntity);

                if ($results instanceof RecordNotFoundException) {
                    Log::write('error', $result);
                    $this->respondError($result->getMessage(), $this->messageHandler->retrieve("Data", "NotRemoved"));
                    $this->response = $this->response->withStatus(404);
                }

                $this->respondSuccess($result, $this->messageHandler->retrieve("Data", "Removed"));
                $this->response = $this->response->withStatus(200);
        } else {
            throw new MethodNotAllowedException("HTTP method disabled for deletion: Use DELETE");
        }
    }
}
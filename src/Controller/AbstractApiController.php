<?php

namespace Prontostoreus\Api\Controller;

use Cake\Controller\Controller as CakeController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Log\Log;

use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\BadRequestException;
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
        try {
            $this->requestFailWhenNot('POST');
        }
        catch (MethodNotAllowedException $ex) {
            Log::write('error', $ex);
            $this->response = $this->response->withStatus(405);
            $this->respondError($ex->getMessage(), $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
            return;
        }

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
                $this->response = $this->response->withStatus(400);
                $this->respondError($entity->getErrors(), $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
            }

            if (!empty($entity) && $hasAssociations) {
                $associatedEntity = $entityModel->saveAssociatedEntity($entityModel, $entity, $data);

                if ($associatedEntity->getErrors()) {
                    Log::write('error', $associatedEntity->getErrors());
                    $this->response = $this->response->withStatus(400);
                    $this->respondError($associatedEntity->getErrors(), $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
                }
            } 

            $contained = $entityModel->getContained(0);
            $created = $entityModel->get($newEntity->id);

            if (!empty($contained)) {
                $created = $entityModel->get($newEntity->id, ['contain' => $contained]);
            }
            
            $this->response = $this->response->withStatus(201);
            $this->respondSuccess($created, $this->messageHandler->retrieve("Data", "Added"));
        }
        else {
            throw new BadRequestException($this->messageHandler->retrieve("Error", "MissingPayload"));
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
        try {
            $this->requestFailWhenNot('GET');
        }
        catch (MethodNotAllowedException $ex) {
            Log::write('error', $ex);
            $this->response = $this->response->withStatus(405);
            $this->respondError($ex->getMessage(), $this->messageHandler->retrieve("Error", "UnsuccessfulView"));
            return;
        }

        if (empty($recordId)) {
            $results = $entityModel->getAll();

            if ($results instanceof RecordNotFoundException) {
                Log::write('error', $results->getMessage());
                $this->response = $this->response->withStatus(404);
                $this->respondError($results->getMessage(), $this->messageHandler->retrieve("Data", "NotFound"));
            }

            $this->response = $this->response->withStatus(200);
            $this->respondSuccess($results->toArray(), $this->messageHandler->retrieve("Data", "Found"));
        }
        else {
            $result = $entityModel->getOne($recordId);

            if ($result instanceof RecordNotFoundException) {
                Log::write('error', $results->getMessage());
                $this->response = $this->response->withStatus(404);
                $this->respondError($result->getMessage(), $this->messageHandler->retrieve("Data", "NotFound"));
            }

            $this->response = $this->response->withStatus(200);
            $this->respondSuccess($result->toArray(), $this->messageHandler->retrieve("Data", "Found"));
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
        try {
            $this->requestFailWhenNot(['PUT', 'POST']);
        }
        catch (MethodNotAllowedException $ex) {
            Log::write('error', $ex);
            $this->response = $this->response->withStatus(405);
            $this->respondError($ex->getMessage(), $this->messageHandler->retrieve("Error", "UnsuccessfulEdit"));
            return;
        }

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

                    $this->response = $this->response->withStatus(200);
                    $this->respondSuccess($updatedRecord, $this->messageHandler->retrieve("Data", "Edited"));
                } else {
                    Log::write('error', $updatedEntity->getErrors());
                    $this->response = $this->response->withStatus(400);
                    $this->respondError($updatedEntity->getErrors(), $this->messageHandler->retrieve("Data", "NotEdited"));
                }
            } catch (RecordNotFoundException $ex) {
                Log::write('error', $ex);
                $this->response = $this->response->withStatus(404);
                $this->respondError($ex->getMessage(), $this->messageHandler->retrieve("Data", "NotFound"));
            }
        }
        else {
            throw new BadRequestException($this->messageHandler->retrieve("Error", "MissingPayload"));
        }
    }

    /**
     * Non-cascading delete of a record of the given Entity type with the given record ID.
     *
     * @param \Cake\ORM\Table $entityModel The entity model to delete
     * @param integer $recordId The ID of the record to delete
     * @throws Cake\Http\Exception\MethodNotAllowedException
     * @return void
     */
    protected function universalRemove(\Cake\ORM\Table $entityModel, int $recordId) 
    {
        try {
            $this->requestFailWhenNot('DELETE');
        }
        catch (MethodNotAllowedException $ex) {
            Log::write('error', $ex);
            $this->response = $this->response->withStatus(405);
            $this->respondError($ex->getMessage(), $this->messageHandler->retrieve("Error", "UnsuccessfulDelete"));
            return;
        }

        $recordEntity = $entityModel->getOne($recordId);
        $result = $entityModel->delete($recordEntity);

        if ($results instanceof RecordNotFoundException) {
            Log::write('error', $result);
            $this->response = $this->response->withStatus(404);
            $this->respondError($result->getMessage(), $this->messageHandler->retrieve("Data", "NotRemoved"));
        }

        $this->response = $this->response->withStatus(200);
        $this->respondSuccess($result, $this->messageHandler->retrieve("Data", "Removed"));
    }
}
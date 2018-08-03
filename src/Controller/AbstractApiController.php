<?php

namespace Prontostoreus\Api\Controller;

use Cake\Controller\Controller as CakeController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;

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
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
            return;
        }

        $data = $this->request->getData();

        try {
            if (!empty($data)) {
                $associated = $entityModel->getAssociations(0);

                if (!empty($associated)) {
                    $newEntity = $entityModel->newEntity($data, ['associated' => $associated]);
                }
                else {
                    $newEntity = $entityModel->newEntity($data);
                }

                $entity = $entityModel->saveEntity($entityModel, $newEntity);

                if ($entity->getErrors()) {
                    $this->respondError($entity->getErrors(), 400, $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
                    return;
                }

                if (!empty($entity) && $hasAssociations) {
                    $associatedEntity = $entityModel->saveAssociatedEntity($entityModel, $entity, $data);

                    if ($associatedEntity->getErrors()) {
                        $this->respondError($associatedEntity->getErrors(), 400, $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
                        return;
                    }
                } 

                $contained = $entityModel->getContained(0);

                if (!empty($contained)) {
                    $created = $entityModel->get($newEntity->id, ['contain' => $contained]);
                }
                else {
                    $created = $entityModel->get($newEntity->id);
                }
                
                $this->respondSuccess($created, 201, $this->messageHandler->retrieve("Data", "Added"));
            }
            else {
                try {
                    throw new BadRequestException();
                }
                catch (BadRequestException $ex) {
                    $this->respondException($ex, $this->messageHandler->retrieve("Error", "MissingPayload"));
                    return;
                }
            }
        }
        catch (\Exception $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "Unknown"), 500);
            return;
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
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "UnsuccessfulView"));
            return;
        }

        try {
            if (empty($recordId)) {
                $results = $entityModel->getAll();

                if ($results instanceof RecordNotFoundException) {
                    $ex = $results;
                    $this->respondException($ex, $this->messageHandler->retrieve("Data", "NotFound"));
                    return;
                }

                $this->respondSuccess($results->toArray(), 200, $this->messageHandler->retrieve("Data", "Found"));
            }
            else {
                $result = $entityModel->getOne($recordId);

                if ($result instanceof RecordNotFoundException) {
                    $ex = $result;
                    $this->respondException($ex, $this->messageHandler->retrieve("Data", "NotFound"));
                    return;
                }

                $this->respondSuccess($result->toArray(), 200, $this->messageHandler->retrieve("Data", "Found"));
            }
        }
        catch (\Exception $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "Unknown"), 500);
            return;
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
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "UnsuccessfulEdit"));
            return;
        }

        $data = $this->request->getData();

        try {
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

                    if ($patchedEntity->getErrors()) {
                        $this->respondError($patchedEntity->getErrors(), 400, $this->messageHandler->retrieve("Data", "NotEdited"));
                        return;
                    }
                        
                    $updatedEntity = $entityModel->saveEntity($entityModel, $patchedEntity);

                    if ($updatedEntity) {
                        $updatedRecord = $entityModel->get($recordId);

                        if ($contained) {
                            $updatedRecord = $entityModel->get($recordId, ['contain' => $contained]);
                        }

                        $this->respondSuccess($updatedRecord, 200, $this->messageHandler->retrieve("Data", "Edited"));
                    } else {
                        $this->respondError($updatedEntity->getErrors(), 400, $this->messageHandler->retrieve("Data", "NotEdited"));
                        return;
                    }
                } catch (RecordNotFoundException $ex) {
                    $this->respondException($ex, $this->messageHandler->retrieve("Data", "NotFound"));
                    return;
                }
            }
            else {
                try {
                    throw new BadRequestException();
                }
                catch (BadRequestException $ex) {
                    $this->respondException($ex, $this->messageHandler->retrieve("Error", "MissingPayload"));
                    return;
                }
            }
        }
        catch (\Exception $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "Unknown"), 500);
            return;
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
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "UnsuccessfulDelete"));
            return;
        }

        try {
            $recordEntity = $entityModel->getOne($recordId);
            $result = $entityModel->delete($recordEntity);

            if ($result instanceof RecordNotFoundException) {
                $ex = $result;
                $this->respondException($ex, $this->messageHandler->retrieve("Data", "NotRemoved"));
                return;
            }

            $this->respondSuccess($result, 200, $this->messageHandler->retrieve("Data", "Removed"));
        }
        catch (\Exception $ex) {
            $this->respondException($ex, $this->messageHandler->retrieve("Error", "Unknown"), 500);
            return;
        }
    }
}
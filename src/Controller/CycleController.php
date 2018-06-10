<?php

namespace Prontostoreus\Api\Controller;

use Cake\Controller\Controller as CakeController;
use Cake\Http\Exception;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;
use Prontostoreus\Api\Utility\MessageHandler;

class CycleController extends CakeController
{
    use CycleHydrationTrait;

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
        // ! Break out into own method for non-associative creations
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (!empty($data)) {
                $newEntity = $entityModel->newEntity($data, ['associated' => [ $this->associated[0]]]);
                $entity = $this->saveEntity($entityModel, $newEntity);

                if (!empty($entity) && $hasAssociations) {
                    $associatedEntity = $this->saveAssociatedEntity($entityModel, $entity, $data);
                } 

                $created = $entityModel->get($newEntity->id, ['contain' => $this->associated[0]]);
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

    protected function universalEdit() 
    {
    }

    protected function universalRemove() 
    {
    }

    protected function setAssociations($associated)
    {
        $this->associated = array_merge($this->associated, $associated);
    }

    protected function setContained($contained)
    {
        $this->contained = array_merge($this->contained, $contained);
    }

    protected function saveEntity(\Cake\ORM\Table $entityModel, \Cake\Datasource\EntityInterface $newEntity)
    {
        if ($entityModel->save($newEntity)) {
            $created = $entityModel->get($newEntity->id);
            return $created;
        } else {
            $this->respondError($newEntity->errors(), $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
            $this->response = $this->response->withStatus(400);
        }
    }

    // ! Move this to the model?
    protected function saveAssociatedEntity(\Cake\ORM\Table $parentModel, \Cake\Datasource\EntityInterface $parentEntity, array $data) : \Cake\Datasource\EntityInterface
    {
        $association = Inflector::tableize($this->associated[0]);
        $associatedEntity = TableRegistry::get($this->associated[0]);

        $data[$association][$this->getForeignKeyName($parentModel->alias())] = $parentEntity->id;
        $newAssociatedEntity = $associatedEntity->newEntity($data[$association]);
        
        $associated = $this->saveEntity($associatedEntity, $newAssociatedEntity);
        return $associated;
    }

    // ! Move this to a generic model location?
    protected function getForeignKeyName(string $tableName) : string
    {
        return Inflector::singularize(Inflector::tableize($tableName)) . '_id';
    }
}

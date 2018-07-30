<?php
namespace Prontostoreus\Api\Model\Table;

use Cake\ORM\Table as CakeTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Inflector;

use Cake\Datasource\Exception\RecordNotFoundException;

abstract class AbstractComponentRepository extends CakeTable
{
    protected $associated = [];
    protected $contained = [];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
    }

    /**
     * Returns the name of the associated entity at the given index
     * @param integer $index The array index to retrieve from the property
     * @return string|array The name of the associated entity, or the array of associated entities
     */
    public function getAssociations($index = null)
    {
        if (is_int($index) && !empty($this->associated)) {
            return $this->associated[$index];
        }

        return $this->associated;
    }

    /**
     * Returns the name of the entities this entity contains, at the given index
     * @param integer $index The array index to retrieve from the property
     * @return string|array The name of the contained entity, or the array of contained entities
     */
    public function getContained($index = null)
    {
        if (is_int($index) && !empty($this->contained)) {
            return $this->contained[$index];
        }

        return $this->contained;
    }

    /**
     * Appends an array of entity names to the array of entities this entity is related to
     * @param array $associated The array of entity names to merge into the current associations
     * @return void
     */
    public function setAssociations(array $associated)
    {
        if (is_array($associated)) {
            $this->associated = array_merge($this->associated, $associated);
        }
    }

    /**
     * Appends an array of entity names to the array of entities this entity returns when queried
     * @param array $associated The array of entity names to merge into the current associations
     * @return void
     */
    public function setContained(array $contained)
    {
        if (is_array($contained)) {
            $this->contained = array_merge($this->contained, $contained);
        }
    }

    /**
     * Wrapper method for saving entities
     * @param \Cake\ORM\Table $model The entitiy through which to save
     * @param \Cake\Datasource\EntityInterface $newEntity The new entity (record) to save
     * @return \Cake\Datasource\EntityInterface The persisted entity object, or the unpersisted $newEntity with errors
     */
    public function saveEntity(\Cake\ORM\Table $model, \Cake\Datasource\EntityInterface $newEntity)
    {
        if ($model->save($newEntity)) {
            $created = $model->get($newEntity->id);
            return $created;
        } else {
            return $newEntity;
        }
    }

    /**
     * Save an entity in relation to its parent entity
     * @param \Cake\ORM\Table $parentModel The entity model through which to save
     * @param \Cake\Datasource\EntityInterface $parentEntity The entity object representing the parent record to save
     * @param array $data The record to persist to the database
     * @return \Cake\Datasource\EntityInterface An object representing the newly created record and its meta data.
     */
    public function saveAssociatedEntity(\Cake\ORM\Table $parentModel, \Cake\Datasource\EntityInterface $parentEntity, array $data) : \Cake\Datasource\EntityInterface
    {
        $association = Inflector::tableize($this->getAssociations(0));
        $associatedEntity = TableRegistry::get($this->getAssociations(0));

        $data[$association][$this->getForeignKeyName($parentModel->getAlias())] = $parentEntity->id;
        $newAssociatedEntity = $associatedEntity->newEntity($data[$association]);
        
        $associated = $this->saveEntity($associatedEntity, $newAssociatedEntity);
        return $associated;
    }

    /**
     * Query the called on model for a particular record
     * @param integer $recordId The ID of the persisted record to retrieve 
     * @throws InvalidArgumentException When the given record ID is classified as empty
     * @return array|RecordNotFoundException The record corresponding to the given ID
     */
    public function getOne(int $recordId)
    {
        if (empty($recordId)) {
            throw new InvalidArgumentException('Unable to retrieve record: A valid ID must be provided.');
        }

        try {
            $result = $this->get($recordId);
            return $result;
        }
        catch (RecordNotFoundException $ex) {
            return $ex;
        }
    }

    /**
     * Query the called on model for all records
     * @return array|RecordNotFoundException The records corresponding to the called on model
     */
    public function getAll()
    {
        try {
            $results = $this->find('all');
            return $results;
        }
        catch (RecordNotFoundException $ex) {
            return $ex;
        }
    }

    /**
     * Get the column name of the called upon table when used as a foreign key following CakePHP conventions
     * @param string $tableName Name of the table to generate a CakePHP convention foreign key column name
     * @return string Foreign key column name
     */
    protected function getForeignKeyName(string $tableName) : string
    {
        return Inflector::singularize(Inflector::tableize($tableName)) . '_id';
    }
}
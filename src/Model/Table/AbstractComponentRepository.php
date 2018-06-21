<?php
namespace Prontostoreus\Api\Model\Table;

use Cake\ORM\Table as CakeTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Inflector;

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

    public function getAssociations($index = null)
    {
        if (is_int($index) && !empty($this->associated)) {
            return $this->associated[$index];
        }

        return $this->associated;
    }

    public function getContained($index = null)
    {
        if (is_int($index) && !empty($this->contained)) {
            return $this->contained[$index];
        }

        return $this->contained;
    }

    public function setAssociations(array $associated)
    {
        if (is_array($contained)) {
            $this->associated = array_merge($this->associated, $associated);
        }
    }

    public function setContained(array $contained)
    {
        if (is_array($contained)) {
            $this->contained = array_merge($this->contained, $contained);
        }
    }

    public function saveEntity(\Cake\ORM\Table $model, \Cake\Datasource\EntityInterface $newEntity)
    {
        if ($model->save($newEntity)) {
            $created = $model->get($newEntity->id);
            return $created;
        } else {
            return $newEntity;
        }
    }

    public function saveAssociatedEntity(\Cake\ORM\Table $parentModel, \Cake\Datasource\EntityInterface $parentEntity, array $data) : \Cake\Datasource\EntityInterface
    {
        $association = Inflector::tableize($this->getAssociations(0));
        $associatedEntity = TableRegistry::get($this->getAssociations(0));

        $data[$association][$this->getForeignKeyName($parentModel->alias())] = $parentEntity->id;
        $newAssociatedEntity = $associatedEntity->newEntity($data[$association]);
        
        $associated = $this->saveEntity($associatedEntity, $newAssociatedEntity);
        return $associated;
    }

    protected function getForeignKeyName(string $tableName) : string
    {
        return Inflector::singularize(Inflector::tableize($tableName)) . '_id';
    }
}
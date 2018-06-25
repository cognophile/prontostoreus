<?php
namespace ApplicationComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * Rooms Model
 *
 * @property \ApplicationComponent\Model\Table\FurnishingsTable|\Cake\ORM\Association\HasMany $Furnishings
 *
 * @method \ApplicationComponent\Model\Entity\Room get($primaryKey, $options = [])
 * @method \ApplicationComponent\Model\Entity\Room newEntity($data = null, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Room[] newEntities(array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Room|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ApplicationComponent\Model\Entity\Room|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ApplicationComponent\Model\Entity\Room patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Room[] patchEntities($entities, array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Room findOrCreate($search, callable $callback = null, $options = [])
 */
class RoomsTable extends AbstractComponentRepository
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('rooms');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Furnishings', [
            'foreignKey' => 'room_id',
            'className' => 'ApplicationComponent.Furnishings'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        return $validator;
    }
}

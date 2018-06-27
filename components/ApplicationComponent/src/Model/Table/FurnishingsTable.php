<?php
namespace ApplicationComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * Furnishings Model
 *
 * @property \ApplicationComponent\Model\Table\RoomsTable|\Cake\ORM\Association\BelongsTo $Rooms
 * @property \ApplicationComponent\Model\Table\ApplicationLinesTable|\Cake\ORM\Association\HasMany $ApplicationLines
 * @property \ApplicationComponent\Model\Table\CompanyFurnishingRatesTable|\Cake\ORM\Association\HasMany $CompanyFurnishingRates
 *
 * @method \ApplicationComponent\Model\Entity\Furnishing get($primaryKey, $options = [])
 * @method \ApplicationComponent\Model\Entity\Furnishing newEntity($data = null, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Furnishing[] newEntities(array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Furnishing|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ApplicationComponent\Model\Entity\Furnishing|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ApplicationComponent\Model\Entity\Furnishing patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Furnishing[] patchEntities($entities, array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Furnishing findOrCreate($search, callable $callback = null, $options = [])
 */
class FurnishingsTable extends AbstractComponentRepository
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

        $this->setTable('furnishings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Rooms', [
            'foreignKey' => 'room_id',
            'className' => 'ApplicationComponent.Rooms'
        ]);
        $this->hasMany('ApplicationLines', [
            'foreignKey' => 'furnishing_id',
            'className' => 'ApplicationComponent.ApplicationLines'
        ]);
        $this->hasMany('CompanyFurnishingRates', [
            'foreignKey' => 'furnishing_id',
            'className' => 'ApplicationComponent.CompanyFurnishingRates'
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
            ->allowEmpty('description');

        $validator
            ->integer('size')
            ->allowEmpty('size');

        $validator
            ->integer('weight')
            ->allowEmpty('weight');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['room_id'], 'Rooms'));

        return $rules;
    }

    public function findByRoomId(Query $query, array $options)
    {
        $roomId = $options['roomId'];
        return $query->where(['room_id' => $roomId])->toArray();
    }
}

<?php
namespace ApplicationComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * ApplicationLines Model
 *
 * @property \ApplicationComponent\Model\Table\ApplicationsTable|\Cake\ORM\Association\BelongsTo $Applications
 * @property \ApplicationComponent\Model\Table\FurnishingsTable|\Cake\ORM\Association\BelongsTo $Furnishings
 *
 * @method \ApplicationComponent\Model\Entity\ApplicationLine get($primaryKey, $options = [])
 * @method \ApplicationComponent\Model\Entity\ApplicationLine newEntity($data = null, array $options = [])
 * @method \ApplicationComponent\Model\Entity\ApplicationLine[] newEntities(array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\ApplicationLine|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ApplicationComponent\Model\Entity\ApplicationLine|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ApplicationComponent\Model\Entity\ApplicationLine patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\ApplicationLine[] patchEntities($entities, array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\ApplicationLine findOrCreate($search, callable $callback = null, $options = [])
 */
class ApplicationLinesTable extends AbstractComponentRepository
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

        $this->setTable('application_lines');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Applications', [
            'foreignKey' => 'applicaition_id',
            'className' => 'ApplicationComponent.Applications'
        ]);
        $this->belongsTo('Furnishings', [
            'foreignKey' => 'furnishing_id',
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
            ->integer('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmpty('quantity');

        $validator
            ->scalar('line_cost')
            ->requirePresence('line_cost', 'create')
            ->notEmpty('line_cost');

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
        $rules->add($rules->existsIn(['aapplication_id'], 'Applications'));
        $rules->add($rules->existsIn(['furnishing_id'], 'Furnishings'));

        return $rules;
    }
}

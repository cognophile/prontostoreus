<?php
namespace ConfirmationComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * Confirmations Model
 *
 * @property \ConfirmationComponent\Model\Table\ApplicationsTable|\Cake\ORM\Association\BelongsTo $Applications
 *
 * @method \ConfirmationComponent\Model\Entity\Confirmation get($primaryKey, $options = [])
 * @method \ConfirmationComponent\Model\Entity\Confirmation newEntity($data = null, array $options = [])
 * @method \ConfirmationComponent\Model\Entity\Confirmation[] newEntities(array $data, array $options = [])
 * @method \ConfirmationComponent\Model\Entity\Confirmation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ConfirmationComponent\Model\Entity\Confirmation|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ConfirmationComponent\Model\Entity\Confirmation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \ConfirmationComponent\Model\Entity\Confirmation[] patchEntities($entities, array $data, array $options = [])
 * @method \ConfirmationComponent\Model\Entity\Confirmation findOrCreate($search, callable $callback = null, $options = [])
 */
class ConfirmationsTable extends AbstractComponentRepository
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

        $this->setTable('confirmations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Applications', [
            'foreignKey' => 'application_id',
            'className' => 'ApplicationsComponent.Applications'
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
            ->boolean('accepted')
            ->requirePresence('accepted', 'create')
            ->notEmpty('accepted');

        $validator
            ->dateTime('date_accepted')
            ->requirePresence('date_accepted', 'create')
            ->notEmpty('date_accepted');

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
        // $rules->add($rules->existsIn(['application_id'], 'ConfirmationComponent.Applications'));
        return $rules;
    }

    /**
     * Search for confirmation records by the application id 
     * 
     * @param \Cake\ORM\Query $query Query instance
     * @param Array $options An array of request argument options
     * @return Array Resultant data set
     */
    public function findByApplicationId(Query $query, array $options)
    {
        $applicationId = $options['application_id'];
        return $query->where(['application_id' => $applicationId]);
    }
}

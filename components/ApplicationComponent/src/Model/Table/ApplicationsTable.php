<?php
namespace ApplicationComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * Applications Model
 *
 * @property \ApplicationComponent\Model\Table\ApplicationLinesTable|\Cake\ORM\Association\HasMany $ApplicationLines
 * @property \ApplicationComponent\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \ApplicationComponent\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \ApplicationComponent\Model\Table\ConfirmationsTable|\Cake\ORM\Association\HasMany $Confirmations
 *
 * @method \ApplicationComponent\Model\Entity\Application get($primaryKey, $options = [])
 * @method \ApplicationComponent\Model\Entity\Application newEntity($data = null, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Application[] newEntities(array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Application|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ApplicationComponent\Model\Entity\Application|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ApplicationComponent\Model\Entity\Application patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Application[] patchEntities($entities, array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Application findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ApplicationsTable extends AbstractComponentRepository
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

        $this->setTable('applications');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('ApplicationLines', [
            'foreignKey' => 'application_id',
            'className' => 'ApplicationComponent.ApplicationLines'
        ]);
        
        // TODO: Remove these inter-component dependencies if not needed
        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'className' => 'CustomerComponent.Customers'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'className' => 'LocationComponent.Companies'
        ]);
        $this->hasMany('Confirmations', [
            'foreignKey' => 'application_id',
            'className' => 'ConfirmationComponent.Confirmations'
        ]);

        $this->setAssociations(['ApplicationLines']);
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
            ->boolean('delivery')
            ->requirePresence('delivery', 'create');

        $validator
            ->date('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date');

        $validator
            ->date('end_date')
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date');

        $validator
            ->scalar('total_cost')
            ->requirePresence('total_cost', 'create')
            ->notEmpty('total_cost');

        $validator
            ->boolean('cancelled')
            ->allowEmpty('cancelled', 'create');

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
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }
}

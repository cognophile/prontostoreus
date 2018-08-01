<?php
namespace ConfirmationComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * Applications Model
 *
 * @property \ConfirmationComponent\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \ConfirmationComponent\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \ConfirmationComponent\Model\Table\ApplicationLinesTable|\Cake\ORM\Association\HasMany $ApplicationLines
 * @property \ConfirmationComponent\Model\Table\ConfirmationsTable|\Cake\ORM\Association\HasMany $Confirmations
 * @property \ConfirmationComponent\Model\Table\InvoicesTable|\Cake\ORM\Association\HasMany $Invoices
 *
 * @method \ConfirmationComponent\Model\Entity\Application get($primaryKey, $options = [])
 * @method \ConfirmationComponent\Model\Entity\Application newEntity($data = null, array $options = [])
 * @method \ConfirmationComponent\Model\Entity\Application[] newEntities(array $data, array $options = [])
 * @method \ConfirmationComponent\Model\Entity\Application|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ConfirmationComponent\Model\Entity\Application|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ConfirmationComponent\Model\Entity\Application patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \ConfirmationComponent\Model\Entity\Application[] patchEntities($entities, array $data, array $options = [])
 * @method \ConfirmationComponent\Model\Entity\Application findOrCreate($search, callable $callback = null, $options = [])
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

        // $this->belongsTo('Customers', [
        //     'foreignKey' => 'customer_id',
        //     'className' => 'ConfirmationComponent.Customers'
        // ]);
        // $this->belongsTo('Companies', [
        //     'foreignKey' => 'company_id',
        //     'className' => 'ConfirmationComponent.Companies'
        // ]);
        // $this->hasMany('ApplicationLines', [
        //     'foreignKey' => 'application_id',
        //     'className' => 'ConfirmationComponent.ApplicationLines'
        // ]);
        $this->hasMany('Confirmations', [
            'foreignKey' => 'application_id',
            'className' => 'ConfirmationComponent.Confirmations'
        ]);
        // $this->hasMany('Invoices', [
        //     'foreignKey' => 'application_id',
        //     'className' => 'ConfirmationComponent.Invoices'
        // ]);
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
            ->requirePresence('delivery', 'create')
            ->notEmpty('delivery');

        $validator
            ->date('start_date')
            ->allowEmpty('start_date');

        $validator
            ->date('end_date')
            ->allowEmpty('end_date');

        $validator
            ->scalar('total_cost')
            ->maxLength('total_cost', 255)
            ->requirePresence('total_cost', 'create')
            ->notEmpty('total_cost');

        $validator
            ->boolean('cancelled')
            ->allowEmpty('cancelled');

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

        /**
     * Search for confirmation records by the application id 
     * 
     * @param \Cake\ORM\Query $query Query instance
     * @param Array $options An array of request argument options
     * @return Array Resultant data set
     */
    public function findById(Query $query, array $options)
    {
        $applicationId = $options['id'];
        return $query->where(['id' => $applicationId]);
    }
}

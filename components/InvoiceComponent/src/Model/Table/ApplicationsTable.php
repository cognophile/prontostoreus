<?php
namespace InvoiceComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * Applications Model
 *
 * @property \InvoiceComponent\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \InvoiceComponent\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \InvoiceComponent\Model\Table\ApplicationLinesTable|\Cake\ORM\Association\HasMany $ApplicationLines
 * @property \InvoiceComponent\Model\Table\ConfirmationsTable|\Cake\ORM\Association\HasMany $Confirmations
 * @property \InvoiceComponent\Model\Table\InvoicesTable|\Cake\ORM\Association\HasMany $Invoices
 *
 * @method \InvoiceComponent\Model\Entity\Application get($primaryKey, $options = [])
 * @method \InvoiceComponent\Model\Entity\Application newEntity($data = null, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Application[] newEntities(array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Application|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \InvoiceComponent\Model\Entity\Application|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \InvoiceComponent\Model\Entity\Application patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Application[] patchEntities($entities, array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Application findOrCreate($search, callable $callback = null, $options = [])
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

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'className' => 'CustomerComponent.Customers'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'className' => 'LocationComponent.Companies'
        ]);
        $this->hasMany('ApplicationLines', [
            'foreignKey' => 'application_id',
            'className' => 'ApplicationComponent.ApplicationLines'
        ]);
        $this->hasMany('Confirmations', [
            'foreignKey' => 'application_id',
            'className' => 'ConfirmationComponent.Confirmations'
        ]);
        $this->hasMany('Invoices', [
            'foreignKey' => 'application_id',
            'className' => 'InvoiceComponent.Invoices'
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

    public function findCustomerByApplicationId(Query $query, array $options)
    {
        $applicationId = $options['applicationId'];

        return $query->find('all')
            ->contain('Customers.Addresses')->where(['Applications.id' => $applicationId])
            ->andWhere(['cancelled' => 0]);
    }

    public function findCompanyByApplicationId(Query $query, array $options)
    {
        $applicationId = $options['applicationId'];

        return $query->find('all')
            ->contain('Companies.Addresses')->where(['Applications.id' => $applicationId])
            ->andWhere(['cancelled' => 0]);
    }

    public function findLinesByApplicationId(Query $query, array $options)
    {
        $applicationId = $options['applicationId'];

        return $query->find('all')->contain('ApplicationLines.Furnishings.Rooms')
            ->where(['Applications.id' => $applicationId])
            ->andWhere(['cancelled' => 0]);
    }
}
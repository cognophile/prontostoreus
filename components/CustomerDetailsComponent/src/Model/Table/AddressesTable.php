<?php
namespace CustomerDetailsComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Addresses Model
 *
 * @property \CustomerDetailsComponent\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \CustomerDetailsComponent\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 *
 * @method \CustomerDetailsComponent\Model\Entity\Address get($primaryKey, $options = [])
 * @method \CustomerDetailsComponent\Model\Entity\Address newEntity($data = null, array $options = [])
 * @method \CustomerDetailsComponent\Model\Entity\Address[] newEntities(array $data, array $options = [])
 * @method \CustomerDetailsComponent\Model\Entity\Address|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \CustomerDetailsComponent\Model\Entity\Address|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \CustomerDetailsComponent\Model\Entity\Address patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \CustomerDetailsComponent\Model\Entity\Address[] patchEntities($entities, array $data, array $options = [])
 * @method \CustomerDetailsComponent\Model\Entity\Address findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AddressesTable extends Table
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

        $this->setTable('addresses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'className' => 'CustomerDetailsComponent.Companies'
        ]);
        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'className' => 'CustomerDetailsComponent.Customers'
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
            ->scalar('line_one')
            ->maxLength('line_one', 128)
            ->requirePresence('line_one', 'create')
            ->notEmpty('line_one');

        $validator
            ->scalar('line_two')
            ->maxLength('line_two', 128)
            ->allowEmpty('line_two');

        $validator
            ->scalar('town')
            ->maxLength('town', 128)
            ->requirePresence('town', 'create')
            ->notEmpty('town');

        $validator
            ->scalar('county')
            ->maxLength('county', 128)
            ->requirePresence('county', 'create')
            ->notEmpty('county');

        $validator
            ->scalar('postcode')
            ->maxLength('postcode', 8)
            ->requirePresence('postcode', 'create')
            ->notEmpty('postcode');

        $validator
            ->boolean('deleted')
            ->requirePresence('deleted', 'create')
            ->notEmpty('deleted');

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
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));

        return $rules;
    }
}

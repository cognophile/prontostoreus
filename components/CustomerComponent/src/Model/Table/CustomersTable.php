<?php
namespace CustomerComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Customers Model
 *
 * @property \CustomerComponent\Model\Table\AddressesTable|\Cake\ORM\Association\HasMany $Addresses
 *
 * @method \CustomerComponent\Model\Entity\Customer get($primaryKey, $options = [])
 * @method \CustomerComponent\Model\Entity\Customer newEntity($data = null, array $options = [])
 * @method \CustomerComponent\Model\Entity\Customer[] newEntities(array $data, array $options = [])
 * @method \CustomerComponent\Model\Entity\Customer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \CustomerComponent\Model\Entity\Customer|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \CustomerComponent\Model\Entity\Customer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \CustomerComponent\Model\Entity\Customer[] patchEntities($entities, array $data, array $options = [])
 * @method \CustomerComponent\Model\Entity\Customer findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomersTable extends Table
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

        $this->setTable('customers');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Addresses', [
            'foreignKey' => 'customer_id',
            'className' => 'CustomerComponent.Addresses',
            'saveStrategy' => 'append'
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
            ->scalar('title')
            ->maxLength('title', 4)
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->scalar('firstname')
            ->maxLength('firstname', 128)
            ->requirePresence('firstname', 'create')
            ->notEmpty('firstname');

        $validator
            ->scalar('surname')
            ->maxLength('surname', 128)
            ->requirePresence('surname', 'create')
            ->notEmpty('surname');

        $validator
            ->date('dob')
            ->requirePresence('dob', 'create')
            ->notEmpty('dob');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->scalar('telephone')
            ->maxLength('telephone', 12)
            ->requirePresence('telephone', 'create')
            ->notEmpty('telephone');

        $validator
            ->boolean('deleted')
            ->allowEmpty('deleted');

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
        /* 
            Disable unique email validation so that customers can apply multiple times 
            with the dame details
        $rules->add($rules->isUnique(['email']));   
        */     
        
        return $rules;
    }
}

<?php
namespace ApplicationComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Companies Model
 *
 * @property \ApplicationComponent\Model\Table\AddressesTable|\Cake\ORM\Association\HasMany $Addresses
 * @property \ApplicationComponent\Model\Table\ApplicationsTable|\Cake\ORM\Association\HasMany $Applications
 * @property \ApplicationComponent\Model\Table\CompanyFurnishingRatesTable|\Cake\ORM\Association\HasMany $CompanyFurnishingRates
 *
 * @method \ApplicationComponent\Model\Entity\Company get($primaryKey, $options = [])
 * @method \ApplicationComponent\Model\Entity\Company newEntity($data = null, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Company[] newEntities(array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Company|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ApplicationComponent\Model\Entity\Company|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ApplicationComponent\Model\Entity\Company patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Company[] patchEntities($entities, array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\Company findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CompaniesTable extends Table
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

        $this->setTable('companies');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        // $this->hasMany('Addresses', [
        //     'foreignKey' => 'company_id',
        //     'className' => 'ApplicationComponent.Addresses'
        // ]);
        $this->hasMany('Applications', [
            'foreignKey' => 'company_id',
            'className' => 'ApplicationComponent.Applications'
        ]);
        $this->hasMany('CompanyFurnishingRates', [
            'foreignKey' => 'company_id',
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
            ->scalar('name')
            ->maxLength('name', 128)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 256)
            ->requirePresence('description', 'create')
            ->notEmpty('description');

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
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}

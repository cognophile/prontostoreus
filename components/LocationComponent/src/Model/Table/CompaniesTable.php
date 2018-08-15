<?php
namespace LocationComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * Companies Model
 *
 * @property \LocationComponent\Model\Table\AddressesTable|\Cake\ORM\Association\HasMany $Addresses
 *
 * @method \LocationComponent\Model\Entity\Company get($primaryKey, $options = [])
 * @method \LocationComponent\Model\Entity\Company newEntity($data = null, array $options = [])
 * @method \LocationComponent\Model\Entity\Company[] newEntities(array $data, array $options = [])
 * @method \LocationComponent\Model\Entity\Company|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \LocationComponent\Model\Entity\Company|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \LocationComponent\Model\Entity\Company patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \LocationComponent\Model\Entity\Company[] patchEntities($entities, array $data, array $options = [])
 * @method \LocationComponent\Model\Entity\Company findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CompaniesTable extends AbstractComponentRepository
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

        $this->hasMany('Addresses', [
            'foreignKey' => 'company_id',
            'className' => 'LocationComponent.Addresses'
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
            ->numeric('telephone')
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
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }

    /**
     * Search for companies according to postcode proximity 
     * 
     * @param \Cake\ORM\Query $query Query instance
     * @param Array $options An array of request argument options
     * @return \Cake\ORM\Query Resultant data object
     */
    public function findByPostcode(Query $query, array $options)
    {   
        $postcodeParts = explode("-", $options['postcode']);

        $query = $this->find();
        
        $query->select(['Companies.id', 'Companies.name', 'Companies.description', 'Companies.telephone'])
            ->matching('Addresses', function ($q) use ($postcodeParts) {
                return $q->select(['Addresses.postcode'])                      
                    ->where(['Addresses.postcode LIKE' => $postcodeParts[0] . '-' . '%', 'Addresses.deleted' => 0])
                    ->order(['Addresses.postcode' => 'ASC']);
        });

        return $query;
    }
}

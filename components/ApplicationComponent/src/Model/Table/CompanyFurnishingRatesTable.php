<?php
namespace ApplicationComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * CompanyFurnishingRates Model
 *
 * @property \ApplicationComponent\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \ApplicationComponent\Model\Table\FurnishingsTable|\Cake\ORM\Association\BelongsTo $Furnishings
 *
 * @method \ApplicationComponent\Model\Entity\CompanyFurnishingRate get($primaryKey, $options = [])
 * @method \ApplicationComponent\Model\Entity\CompanyFurnishingRate newEntity($data = null, array $options = [])
 * @method \ApplicationComponent\Model\Entity\CompanyFurnishingRate[] newEntities(array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\CompanyFurnishingRate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ApplicationComponent\Model\Entity\CompanyFurnishingRate|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ApplicationComponent\Model\Entity\CompanyFurnishingRate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\CompanyFurnishingRate[] patchEntities($entities, array $data, array $options = [])
 * @method \ApplicationComponent\Model\Entity\CompanyFurnishingRate findOrCreate($search, callable $callback = null, $options = [])
 */
class CompanyFurnishingRatesTable extends AbstractComponentRepository
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

        $this->setTable('company_furnishing_rates');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'className' => 'LocationComponent.Companies'
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
            ->scalar('cost')
            ->maxLength('cost', 255)
            ->allowEmpty('cost');

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
        $rules->add($rules->existsIn(['company_id'], 'Companies'));
        $rules->add($rules->existsIn(['furnishing_id'], 'Furnishings'));

        return $rules;
    }

    public function findCompanyItemPrice(Query $query, array $options)
    {
        return $query->select(['company_id', 'furnishing_id', 'cost'])
            ->where(['company_id' => $options['companyId']])
            ->where(['furnishing_id' => $options['furnishingId']])
            ->first()->toArray();
    }
}

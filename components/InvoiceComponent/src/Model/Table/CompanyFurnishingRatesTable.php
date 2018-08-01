<?php
namespace InvoiceComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * CompanyFurnishingRates Model
 *
 * @property \InvoiceComponent\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \InvoiceComponent\Model\Table\FurnishingsTable|\Cake\ORM\Association\BelongsTo $Furnishings
 *
 * @method \InvoiceComponent\Model\Entity\CompanyFurnishingRate get($primaryKey, $options = [])
 * @method \InvoiceComponent\Model\Entity\CompanyFurnishingRate newEntity($data = null, array $options = [])
 * @method \InvoiceComponent\Model\Entity\CompanyFurnishingRate[] newEntities(array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\CompanyFurnishingRate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \InvoiceComponent\Model\Entity\CompanyFurnishingRate|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \InvoiceComponent\Model\Entity\CompanyFurnishingRate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\CompanyFurnishingRate[] patchEntities($entities, array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\CompanyFurnishingRate findOrCreate($search, callable $callback = null, $options = [])
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
            'className' => 'InvoiceComponent.Companies'
        ]);
        $this->belongsTo('Furnishings', [
            'foreignKey' => 'furnishing_id',
            'className' => 'InvoiceComponent.Furnishings'
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
}

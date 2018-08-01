<?php
namespace InvoiceComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * Furnishings Model
 *
 * @property \InvoiceComponent\Model\Table\RoomsTable|\Cake\ORM\Association\BelongsTo $Rooms
 * @property \InvoiceComponent\Model\Table\ApplicationLinesTable|\Cake\ORM\Association\HasMany $ApplicationLines
 * @property \InvoiceComponent\Model\Table\CompanyFurnishingRatesTable|\Cake\ORM\Association\HasMany $CompanyFurnishingRates
 *
 * @method \InvoiceComponent\Model\Entity\Furnishing get($primaryKey, $options = [])
 * @method \InvoiceComponent\Model\Entity\Furnishing newEntity($data = null, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Furnishing[] newEntities(array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Furnishing|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \InvoiceComponent\Model\Entity\Furnishing|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \InvoiceComponent\Model\Entity\Furnishing patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Furnishing[] patchEntities($entities, array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Furnishing findOrCreate($search, callable $callback = null, $options = [])
 */
class FurnishingsTable extends AbstractComponentRepository
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

        $this->setTable('furnishings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Rooms', [
            'foreignKey' => 'room_id',
            'className' => 'InvoiceComponent.Rooms'
        ]);
        $this->hasMany('ApplicationLines', [
            'foreignKey' => 'furnishing_id',
            'className' => 'InvoiceComponent.ApplicationLines'
        ]);
        $this->hasMany('CompanyFurnishingRates', [
            'foreignKey' => 'furnishing_id',
            'className' => 'InvoiceComponent.CompanyFurnishingRates'
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
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmpty('description');

        $validator
            ->integer('size')
            ->requirePresence('size', 'create')
            ->allowEmpty('size');

        $validator
            ->integer('weight')
            ->requirePresence('weight', 'create')
            ->allowEmpty('weight');

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
        $rules->add($rules->existsIn(['room_id'], 'Rooms'));

        return $rules;
    }
}

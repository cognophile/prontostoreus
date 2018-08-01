<?php
namespace InvoiceComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * ApplicationLines Model
 *
 * @property \InvoiceComponent\Model\Table\ApplicationsTable|\Cake\ORM\Association\BelongsTo $Applications
 * @property \InvoiceComponent\Model\Table\FurnishingsTable|\Cake\ORM\Association\BelongsTo $Furnishings
 *
 * @method \InvoiceComponent\Model\Entity\ApplicationLine get($primaryKey, $options = [])
 * @method \InvoiceComponent\Model\Entity\ApplicationLine newEntity($data = null, array $options = [])
 * @method \InvoiceComponent\Model\Entity\ApplicationLine[] newEntities(array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\ApplicationLine|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \InvoiceComponent\Model\Entity\ApplicationLine|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \InvoiceComponent\Model\Entity\ApplicationLine patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\ApplicationLine[] patchEntities($entities, array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\ApplicationLine findOrCreate($search, callable $callback = null, $options = [])
 */
class ApplicationLinesTable extends AbstractComponentRepository
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

        $this->setTable('application_lines');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Applications', [
            'foreignKey' => 'application_id',
            'className' => 'InvoiceComponent.Applications'
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
            ->integer('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmpty('quantity');

        $validator
            ->scalar('line_cost')
            ->maxLength('line_cost', 255)
            ->requirePresence('line_cost', 'create')
            ->notEmpty('line_cost');

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
        $rules->add($rules->existsIn(['application_id'], 'Applications'));
        $rules->add($rules->existsIn(['furnishing_id'], 'Furnishings'));

        return $rules;
    }
}

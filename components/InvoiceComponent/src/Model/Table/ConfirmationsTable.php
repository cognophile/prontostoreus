<?php
namespace InvoiceComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * Confirmations Model
 *
 * @property \InvoiceComponent\Model\Table\ApplicationsTable|\Cake\ORM\Association\BelongsTo $Applications
 *
 * @method \InvoiceComponent\Model\Entity\Confirmation get($primaryKey, $options = [])
 * @method \InvoiceComponent\Model\Entity\Confirmation newEntity($data = null, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Confirmation[] newEntities(array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Confirmation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \InvoiceComponent\Model\Entity\Confirmation|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \InvoiceComponent\Model\Entity\Confirmation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Confirmation[] patchEntities($entities, array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Confirmation findOrCreate($search, callable $callback = null, $options = [])
 */
class ConfirmationsTable extends AbstractComponentRepository
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

        $this->setTable('confirmations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Applications', [
            'foreignKey' => 'application_id',
            'className' => 'InvoiceComponent.Applications'
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
            ->boolean('accepted')
            ->requirePresence('accepted', 'create')
            ->notEmpty('accepted');

        $validator
            ->dateTime('date_accepted')
            ->requirePresence('date_accepted', 'create')
            ->notEmpty('date_accepted');

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

        return $rules;
    }
}

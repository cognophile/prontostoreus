<?php
namespace InvoiceComponent\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Prontostoreus\Api\Model\Table\AbstractComponentRepository;

/**
 * Invoices Model
 *
 * @property \InvoiceComponent\Model\Table\ApplicationsTable|\Cake\ORM\Association\BelongsTo $Applications
 *
 * @method \InvoiceComponent\Model\Entity\Invoice get($primaryKey, $options = [])
 * @method \InvoiceComponent\Model\Entity\Invoice newEntity($data = null, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Invoice[] newEntities(array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Invoice|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \InvoiceComponent\Model\Entity\Invoice|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \InvoiceComponent\Model\Entity\Invoice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Invoice[] patchEntities($entities, array $data, array $options = [])
 * @method \InvoiceComponent\Model\Entity\Invoice findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InvoicesTable extends AbstractComponentRepository
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

        $this->setTable('invoices');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

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
            ->scalar('reference')
            ->maxLength('reference', 255)
            ->requirePresence('reference', 'create')
            ->notEmpty('reference');

        $validator
            ->scalar('subject')
            ->maxLength('subject', 255)
            ->requirePresence('subject', 'create')
            ->notEmpty('subject');

        $validator
            ->dateTime('issued')
            ->requirePresence('issued', 'create')
            ->notEmpty('issued');

        $validator
            ->dateTime('due')
            ->requirePresence('due', 'create')
            ->notEmpty('due');

        $validator
            ->scalar('paid')
            ->maxLength('paid', 255)
            ->requirePresence('paid', 'create')
            ->notEmpty('paid');

        $validator
            ->scalar('total')
            ->maxLength('total', 255)
            ->requirePresence('total', 'create')
            ->notEmpty('total');

        $validator
            ->boolean('complete')
            ->requirePresence('complete', 'create')
            ->notEmpty('complete');

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

    public function buildInvoiceData(array $applicationData, string $firstname, string $surname): array
    {
        $invoiceData = [];
        $invoiceData['application_id'] = $applicationData['id'];
        $invoiceData['reference'] = $this->generateReferenceCode(['firstname' => $firstname, 'surname' => $surname], $applicationData['created']);
        $invoiceData['subject'] = $invoiceData['reference'] . ': ' . 'Self-storage Application';
        $invoiceData['total'] = $applicationData['total_cost'];

        return $invoiceData;
    }

    private function generateReferenceCode(array $fullname, string $created)
    {
        $prefix = strtoupper(substr($fullname['firstname'], 0, 2) . substr($fullname['surname'], 0, 2));
        $date = explode('T', $created);
        $suffix = str_replace("-", "", $date[0]);

        return "{$prefix}{$suffix}";
    }
}

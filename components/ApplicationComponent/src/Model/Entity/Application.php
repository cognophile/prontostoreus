<?php
namespace ApplicationComponent\Model\Entity;

use Cake\ORM\Entity;

/**
 * Application Entity
 *
 * @property int $id
 * @property int $customer_id
 * @property int $company_id
 * @property bool $delivery
 * @property \Cake\I18n\FrozenDate $start_date
 * @property \Cake\I18n\FrozenDate $end_date
 * @property string $total_cost
 * @property \Cake\I18n\FrozenTime $created
 * @property bool $cancelled
 *
 * @property \ApplicationComponent\Model\Entity\ApplicationLine[] $application_lines
 * @property \CustomersComponent\Model\Entity\Customer $customer
 * @property \LocationComponent\Model\Entity\Company $company
 * @property \ConfirmationsComponent\Model\Entity\Confirmation[] $confirmations
 */
class Application extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'customer_id' => true,
        'company_id' => true,
        'delivery' => true,
        'start_date' => true,
        'end_date' => true,
        'total_cost' => true,
        'created' => true,
        'cancelled' => true,
        'application_lines' => true,
        'customer' => true,
        'company' => true,
        'confirmations' => true
    ];
}

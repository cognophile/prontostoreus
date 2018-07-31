<?php
namespace ConfirmationComponent\Model\Entity;

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
 * @property \ConfirmationComponent\Model\Entity\Customer $customer
 * @property \ConfirmationComponent\Model\Entity\Company $company
 * @property \ConfirmationComponent\Model\Entity\ApplicationLine[] $application_lines
 * @property \ConfirmationComponent\Model\Entity\Confirmation[] $confirmations
 * @property \ConfirmationComponent\Model\Entity\Invoice[] $invoices
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
        'customer' => true,
        'company' => true,
        'application_lines' => true,
        'confirmations' => true,
        'invoices' => true
    ];
}

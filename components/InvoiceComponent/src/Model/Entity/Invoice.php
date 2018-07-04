<?php
namespace InvoiceComponent\Model\Entity;

use Cake\ORM\Entity;

/**
 * Invoice Entity
 *
 * @property int $id
 * @property int $application_id
 * @property string $reference
 * @property string $subject
 * @property \Cake\I18n\FrozenTime $issued
 * @property \Cake\I18n\FrozenTime $due
 * @property string $paid
 * @property string $total
 * @property \Cake\I18n\FrozenTime $created
 * @property bool $complete
 *
 * @property \InvoiceComponent\Model\Entity\Application $application
 */
class Invoice extends Entity
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
        'application_id' => true,
        'reference' => true,
        'subject' => true,
        'issued' => true,
        'due' => true,
        'paid' => true,
        'total' => true,
        'created' => true,
        'complete' => true,
        'application' => true
    ];
}

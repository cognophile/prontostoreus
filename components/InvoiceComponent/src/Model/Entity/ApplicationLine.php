<?php
namespace InvoiceComponent\Model\Entity;

use Cake\ORM\Entity;

/**
 * ApplicationLine Entity
 *
 * @property int $id
 * @property int $application_id
 * @property int $furnishing_id
 * @property int $quantity
 * @property string $line_cost
 *
 * @property \InvoiceComponent\Model\Entity\Application $application
 * @property \InvoiceComponent\Model\Entity\Furnishing $furnishing
 */
class ApplicationLine extends Entity
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
        'furnishing_id' => true,
        'quantity' => true,
        'line_cost' => true,
        'application' => true,
        'furnishing' => true
    ];
}

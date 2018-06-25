<?php
namespace ApplicationComponent\Model\Entity;

use Cake\ORM\Entity;

/**
 * ApplicationLine Entity
 *
 * @property int $id
 * @property int $applicaition_id
 * @property int $furnishing_id
 * @property int $quantity
 * @property float $line_cost
 *
 * @property \ApplicationComponent\Model\Entity\Application $application
 * @property \ApplicationComponent\Model\Entity\Furnishing $furnishing
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
        'applicaition_id' => true,
        'furnishing_id' => true,
        'quantity' => true,
        'line_cost' => true,
        'application' => true,
        'furnishing' => true
    ];
}

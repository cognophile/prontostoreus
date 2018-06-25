<?php
namespace ApplicationComponent\Model\Entity;

use Cake\ORM\Entity;

/**
 * Furnishing Entity
 *
 * @property int $id
 * @property string $description
 * @property int $size
 * @property int $weight
 * @property int $room_id
 *
 * @property \ApplicationComponent\Model\Entity\Room $room
 * @property \ApplicationComponent\Model\Entity\ApplicationLine[] $application_lines
 * @property \ApplicationComponent\Model\Entity\CompanyFurnishingRate[] $company_furnishing_rates
 */
class Furnishing extends Entity
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
        'description' => true,
        'size' => true,
        'weight' => true,
        'room_id' => true,
        'room' => true,
        'application_lines' => true,
        'company_furnishing_rates' => true
    ];
}

<?php
namespace InvoiceComponent\Model\Entity;

use Cake\ORM\Entity;

/**
 * CompanyFurnishingRate Entity
 *
 * @property int $id
 * @property int $company_id
 * @property int $furnishing_id
 * @property string $cost
 * @property bool $deleted
 *
 * @property \InvoiceComponent\Model\Entity\Company $company
 * @property \InvoiceComponent\Model\Entity\Furnishing $furnishing
 */
class CompanyFurnishingRate extends Entity
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
        'company_id' => true,
        'furnishing_id' => true,
        'cost' => true,
        'deleted' => true,
        'company' => true,
        'furnishing' => true
    ];
}

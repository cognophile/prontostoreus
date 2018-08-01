<?php
namespace InvoiceComponent\Model\Entity;

use Cake\ORM\Entity;

/**
 * Company Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $email
 * @property string $telephone
 * @property \Cake\I18n\FrozenTime $created
 * @property bool $deleted
 *
 * @property \InvoiceComponent\Model\Entity\Address[] $addresses
 * @property \InvoiceComponent\Model\Entity\Application[] $applications
 * @property \InvoiceComponent\Model\Entity\CompanyFurnishingRate[] $company_furnishing_rates
 */
class Company extends Entity
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
        'name' => true,
        'description' => true,
        'email' => true,
        'telephone' => true,
        'created' => true,
        'deleted' => true,
        'addresses' => true,
        'applications' => true,
        'company_furnishing_rates' => true
    ];
}

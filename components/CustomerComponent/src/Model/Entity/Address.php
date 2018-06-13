<?php
namespace CustomerComponent\Model\Entity;

use Cake\ORM\Entity;

/**
 * Address Entity
 *
 * @property int $id
 * @property int $company_id
 * @property string $line_one
 * @property string $line_two
 * @property string $town
 * @property string $county
 * @property string $postcode
 * @property \Cake\I18n\FrozenTime $created
 * @property bool $deleted
 * @property int $customer_id
 *
 * @property \CustomerComponent\Model\Entity\Company $company
 * @property \CustomerComponent\Model\Entity\Customer $customer
 */
class Address extends Entity
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
        'line_one' => true,
        'line_two' => true,
        'town' => true,
        'county' => true,
        'postcode' => true,
        'created' => true,
        'deleted' => true,
        'customer_id' => true,
        'company' => true,
        'customer' => true
    ];
}

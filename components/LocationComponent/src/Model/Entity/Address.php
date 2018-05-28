<?php
namespace LocationComponent\Model\Entity;

use Cake\ORM\Entity;

/**
 * Address Entity
 *
 * @property int $id
 * @property string $line_one
 * @property string $line_two
 * @property string $town
 * @property string $county
 * @property string $postcode
 *
 * @property \LocationComponent\Model\Entity\Company[] $companies
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
        'line_one' => true,
        'line_two' => true,
        'town' => true,
        'county' => true,
        'postcode' => true,
        'companies' => true
    ];
}

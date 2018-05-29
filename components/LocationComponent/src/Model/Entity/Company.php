<?php
namespace LocationComponent\Model\Entity;

use Cake\ORM\Entity;

/**
 * Company Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $email
 * @property string $telephone
 *
 * @property \LocationComponent\Model\Entity\Address[] $addresses
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
        'addresses' => true
    ];
}

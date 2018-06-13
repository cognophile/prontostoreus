<?php
namespace CustomerComponent\Model\Entity;

use Cake\ORM\Entity;

/**
 * Customer Entity
 *
 * @property int $id
 * @property string $title
 * @property string $firstname
 * @property string $surname
 * @property \Cake\I18n\FrozenTime $dob
 * @property string $email
 * @property string $telephone
 * @property \Cake\I18n\FrozenTime $created
 * @property bool $deleted
 *
 * @property \CustomerComponent\Model\Entity\Address[] $addresses
 */
class Customer extends Entity
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
        'title' => true,
        'firstname' => true,
        'surname' => true,
        'dob' => true,
        'email' => true,
        'telephone' => true,
        'created' => true,
        'deleted' => true,
        'addresses' => true
    ];
}

<?php
namespace ConfirmationComponent\Model\Entity;

use Cake\ORM\Entity;

/**
 * Confirmation Entity
 *
 * @property int $id
 * @property int $application_id
 * @property bool $accepted
 * @property \Cake\I18n\FrozenTime $date_accepted
 *
 * @property \ConfirmationComponent\Model\Entity\Application $application
 */
class Confirmation extends Entity
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
        'accepted' => true,
        'date_accepted' => true
    ];
}

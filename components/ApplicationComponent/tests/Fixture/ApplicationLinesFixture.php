<?php
namespace ApplicationComponent\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ApplicationLinesFixture
 *
 */
class ApplicationLinesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'applicaition_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'furnishing_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'quantity' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'line_cost' => ['type' => 'decimal', 'length' => 10, 'precision' => 0, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => ''],
        '_indexes' => [
            'applicaition_id' => ['type' => 'index', 'columns' => ['applicaition_id'], 'length' => []],
            'furnishing_id' => ['type' => 'index', 'columns' => ['furnishing_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'application_lines_ibfk_1' => ['type' => 'foreign', 'columns' => ['applicaition_id'], 'references' => ['applications', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'application_lines_ibfk_2' => ['type' => 'foreign', 'columns' => ['furnishing_id'], 'references' => ['furnishings', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'applicaition_id' => 1,
                'furnishing_id' => 1,
                'quantity' => 1,
                'line_cost' => 1.5
            ],
        ];
        parent::init();
    }
}

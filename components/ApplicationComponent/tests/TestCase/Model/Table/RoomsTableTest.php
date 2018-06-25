<?php
namespace ApplicationComponent\Test\TestCase\Model\Table;

use ApplicationComponent\Model\Table\RoomsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * ApplicationComponent\Model\Table\RoomsTable Test Case
 */
class RoomsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \ApplicationComponent\Model\Table\RoomsTable
     */
    public $Rooms;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.application_component.rooms',
        'plugin.application_component.furnishings'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Rooms') ? [] : ['className' => RoomsTable::class];
        $this->Rooms = TableRegistry::getTableLocator()->get('Rooms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Rooms);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

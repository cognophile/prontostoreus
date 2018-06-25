<?php
namespace ApplicationComponent\Test\TestCase\Model\Table;

use ApplicationComponent\Model\Table\FurnishingsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * ApplicationComponent\Model\Table\FurnishingsTable Test Case
 */
class FurnishingsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \ApplicationComponent\Model\Table\FurnishingsTable
     */
    public $Furnishings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.application_component.furnishings',
        'plugin.application_component.rooms',
        'plugin.application_component.application_lines',
        'plugin.application_component.company_furnishing_rates'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Furnishings') ? [] : ['className' => FurnishingsTable::class];
        $this->Furnishings = TableRegistry::getTableLocator()->get('Furnishings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Furnishings);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

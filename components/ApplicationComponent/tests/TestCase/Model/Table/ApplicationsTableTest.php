<?php
namespace ApplicationComponent\Test\TestCase\Model\Table;

use ApplicationComponent\Model\Table\ApplicationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * ApplicationComponent\Model\Table\ApplicationsTable Test Case
 */
class ApplicationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \ApplicationComponent\Model\Table\ApplicationsTable
     */
    public $Applications;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.application_component.applications',
        'plugin.application_component.customers',
        'plugin.application_component.companies',
        'plugin.application_component.confirmations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Applications') ? [] : ['className' => ApplicationsTable::class];
        $this->Applications = TableRegistry::getTableLocator()->get('Applications', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Applications);

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

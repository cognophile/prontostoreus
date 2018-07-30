<?php
namespace ApplicationComponent\Test\TestCase\Model\Table;

use ApplicationComponent\Model\Table\CompaniesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * ApplicationComponent\Model\Table\CompaniesTable Test Case
 */
class CompaniesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \ApplicationComponent\Model\Table\CompaniesTable
     */
    public $Companies;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.application_component.companies',
        // 'plugin.application_component.addresses',
        'plugin.application_component.applications',
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
        $config = TableRegistry::getTableLocator()->exists('Companies') ? [] : ['className' => CompaniesTable::class];
        $this->Companies = TableRegistry::getTableLocator()->get('Companies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Companies);

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

<?php
namespace CustomerComponent\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CustomerComponent\Model\Table\AddressesTable;

/**
 * CustomerComponent\Model\Table\AddressesTable Test Case
 */
class AddressesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \CustomerComponent\Model\Table\AddressesTable
     */
    public $Addresses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.customer_component.addresses',
        'plugin.customer_component.customers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Addresses') ? [] : ['className' => AddressesTable::class];
        $this->Addresses = TableRegistry::getTableLocator()->get('Addresses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Addresses);

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

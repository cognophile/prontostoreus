<?php
namespace InvoiceComponent\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use InvoiceComponent\Model\Table\FurnishingsTable;

/**
 * InvoiceComponent\Model\Table\FurnishingsTable Test Case
 */
class FurnishingsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \InvoiceComponent\Model\Table\FurnishingsTable
     */
    public $Furnishings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.invoice_component.furnishings',
        'plugin.invoice_component.rooms',
        'plugin.invoice_component.application_lines',
        'plugin.invoice_component.company_furnishing_rates'
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

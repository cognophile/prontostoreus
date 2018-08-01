<?php
namespace InvoiceComponent\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use InvoiceComponent\Model\Table\CompanyFurnishingRatesTable;

/**
 * InvoiceComponent\Model\Table\CompanyFurnishingRatesTable Test Case
 */
class CompanyFurnishingRatesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \InvoiceComponent\Model\Table\CompanyFurnishingRatesTable
     */
    public $CompanyFurnishingRates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.invoice_component.company_furnishing_rates',
        'plugin.invoice_component.companies',
        'plugin.invoice_component.furnishings'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CompanyFurnishingRates') ? [] : ['className' => CompanyFurnishingRatesTable::class];
        $this->CompanyFurnishingRates = TableRegistry::getTableLocator()->get('CompanyFurnishingRates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CompanyFurnishingRates);

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

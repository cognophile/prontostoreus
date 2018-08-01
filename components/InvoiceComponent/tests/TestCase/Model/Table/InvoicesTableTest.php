<?php
namespace InvoiceComponent\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use InvoiceComponent\Model\Table\InvoicesTable;

/**
 * InvoiceComponent\Model\Table\InvoicesTable Test Case
 */
class InvoicesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \InvoiceComponent\Model\Table\InvoicesTable
     */
    public $Invoices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.invoice_component.invoices',
        'plugin.invoice_component.applications'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Invoices') ? [] : ['className' => InvoicesTable::class];
        $this->Invoices = TableRegistry::getTableLocator()->get('Invoices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Invoices);

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

    public function testBuildInvoiceData()
    {
        $expected = [
            'application_id' => 1,
            'reference' => 'JOSM20180107',
            'subject' => 'JOSM20180107: Self-storage Application',
            'due' => '2018-08-06 16:45:44',
            'issued' => '2018-07-06 16:45:44',
            'total' => '34.67'
        ];

        $customerData = ['firstname' => 'John', 'surname' => 'Smith'];
        $applicationData = ['id' => 1, 'end_date' => '2018-07-06T16:45:44+00:00', 'created' => '2018-01-07T16:45:44+00:00', 'total_cost' => '34.67'];
        $invoiceData = $this->Invoices->buildInvoiceData($applicationData, $customerData['firstname'], $customerData['surname']);

        $this->assertEquals($expected, $invoiceData);
    }

    public function testReferenceCodeGeneration()
    {
        $expected = 'JOSM20180107';
        
        $customerData = ['firstname' => 'John', 'surname' => 'Smith'];
        $applicationData = ['id' => 1, 'end_date' => '2018-07-06T16:45:44+00:00', 'created' => '2018-01-07T16:45:44+00:00', 'total_cost' => '34.67'];
        $invoiceData = $this->Invoices->buildInvoiceData($applicationData, $customerData['firstname'], $customerData['surname']);

        $this->assertTextEquals($expected, $invoiceData['reference']);
    }
}

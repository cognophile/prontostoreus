<?php
namespace InvoiceComponent\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use InvoiceComponent\Model\Table\ConfirmationsTable;

/**
 * InvoiceComponent\Model\Table\ConfirmationsTable Test Case
 */
class ConfirmationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \InvoiceComponent\Model\Table\ConfirmationsTable
     */
    public $Confirmations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.invoice_component.confirmations',
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
        $config = TableRegistry::getTableLocator()->exists('Confirmations') ? [] : ['className' => ConfirmationsTable::class];
        $this->Confirmations = TableRegistry::getTableLocator()->get('Confirmations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Confirmations);

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

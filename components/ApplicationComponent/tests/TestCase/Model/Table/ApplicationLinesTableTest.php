<?php
namespace ApplicationComponent\Test\TestCase\Model\Table;

use ApplicationComponent\Model\Table\ApplicationLinesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * ApplicationComponent\Model\Table\ApplicationLinesTable Test Case
 */
class ApplicationLinesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \ApplicationComponent\Model\Table\ApplicationLinesTable
     */
    public $ApplicationLines;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.application_component.application_lines',
        'plugin.application_component.applications',
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
        $config = TableRegistry::getTableLocator()->exists('ApplicationLines') ? [] : ['className' => ApplicationLinesTable::class];
        $this->ApplicationLines = TableRegistry::getTableLocator()->get('ApplicationLines', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ApplicationLines);

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

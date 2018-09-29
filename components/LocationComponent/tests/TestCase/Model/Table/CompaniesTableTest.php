<?php
namespace LocationComponent\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use LocationComponent\Model\Table\CompaniesTable;

/**
 * LocationComponent\Model\Table\CompaniesTable Test Case
 */
class CompaniesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \LocationComponent\Model\Table\CompaniesTable
     */
    public $Companies;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.location_component.companies',
        'plugin.location_component.addresses'
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

    /**
     * Test findByPostcode method
     *
     * @return void
     */
    public function testFindByPostcode()
    {
        $postcode = 'AB12-1DE';
        $expected = [
            [
                'id' => 1, 
                'name' => "Lorem ipsum", 
                'description' => "dolor sit amet", 
                'telephone' => "01234567789", 
                "_matchingData" => 
                    [
                        "Addresses" => [
                            "postcode" => "AB12-1DE"
                            ]
                        ]
                    ]
        ]; 

        $query = $this->Companies->find('byPostcode', ['postcode' => $postcode]);
        $this->assertInstanceOf('Cake\ORM\Query', $query);

        $actual = $query->enableHydration(false)->toArray();
        $this->assertEquals($expected, $actual);
    }
}

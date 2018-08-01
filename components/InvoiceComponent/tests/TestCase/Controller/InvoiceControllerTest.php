<?php
namespace InvoiceComponent\Test\TestCase\Controller;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;

use InvoiceComponent\Controller\InvoiceController;

/**
 * InvoiceComponent\Controller\InvoiceController Test Case
 */
class InvoiceControllerTest extends IntegrationTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.invoice_component.invoices',
        'plugin.invoice_component.applications',
        'plugin.invoice_component.application_lines',
        'plugin.invoice_component.addresses',
        'plugin.invoice_component.customers',
        'plugin.invoice_component.companies',
        'plugin.invoice_component.confirmations',
        'plugin.invoice_component.rooms',
        'plugin.invoice_component.furnishings',
        'plugin.invoice_component.company_furnishing_rates',
    ];

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
     * Test status method
     *
     * @return void
     */
    public function testStatus()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testGetInvoicesComponentStatusRouteWhereResponseIsSuccessful()
    {
        $this->get('/invoices');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    public function testMultipleGetInvoicesComponentStatusRouteInSuccessionIsStable()
    {
        $this->get('/invoices');
        $this->assertResponseOk();

        $this->get('/invoices');
        $this->assertResponseOk();
    }

    public function testGetInvoicesComponentStatusRouteResponseIsJsonFormat()
    {
        $this->get('/invoices');
        $this->assertContentType('application/json');
    }

    public function testGetInvoicesComponentStatusRouteResponseIsNotEmpty()
    {
        $this->get('/invoices');
        $this->assertResponseNotEmpty();
    }

    public function testGetInvoicesComponentStatusRouteResponseStructure()
    {
        $this->get('/invoices');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('url', $responseArray);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertArrayHasKey('links', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
    }

    /**
     * Test getApplicationCustomer method
     *
     * @return void
     */
    public function testGetApplicationCustomer()
    {
        $applicationId = 1;
        $query = TableRegistry::get('InvoiceComponent.Applications')->find('all')
            ->contain('Customers.Addresses')->where(['Applications.id' => $applicationId])
            ->andWhere(['cancelled' => 0]);
        $expected = $query->enableHydration(false)->toArray();

        $this->get("/invoices/applications/{$applicationId}/customer");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertEquals($expected, $responseArray['data']);
    }






    /**
     * Test getApplicationCompany method
     *
     * @return void
     */
    public function testGetApplicationCompany()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }






    /**
     * Test getApplicationMetadata method
     *
     * @return void
     */
    public function testGetApplicationMetadata()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }






    /**
     * Test getApplicaitonLines method
     *
     * @return void
     */
    public function testGetApplicaitonLines()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }






    /**
     * Test produceInvoice method
     *
     * @return void
     */
    public function testProduceInvoice()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

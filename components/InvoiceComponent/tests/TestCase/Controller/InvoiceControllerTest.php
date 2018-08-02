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

    public function testGetApplicationCustomerWithValidApplicationIdReturnsSuccessfulResponseAndMatchingRecord()
    {
        $applicationId = 1;
        $expectedMessage = "The data was successfully located";

        $this->get("/invoices/applications/{$applicationId}/customer");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertTrue($responseArray['success']);
        $this->assertContains($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationCustomerWithInvalidNonNumericApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = "A";
        $expectedError = 'A valid application ID must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get("/invoices/applications/{$applicationId}/customer");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationCustomerWithInvalidSymbolicApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = "@";
        $expectedError = 'A valid application ID must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get("/invoices/applications/{$applicationId}/customer");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationCustomerWithNonExistentApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = 999;
        $expectedError = "Requested customer has no application";
        $expectedMessage = "The requested data could not be located";

        $query = TableRegistry::get('InvoiceComponent.Applications')->find('all')
            ->contain('Customers.Addresses')->where(['Applications.id' => $applicationId])
            ->andWhere(['cancelled' => 0]);
        $expected = $query->enableHydration(false)->toArray();

        $this->get("/invoices/applications/{$applicationId}/customer");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(404);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
        $this->assertEquals($expected, $responseArray['data']);
    }
    
    public function testGetApplicationCompanyWithValidApplicationIdReturnsSuccessfulResponseAndMatchingRecord()
    {
        $applicationId = 1;
        $expectedMessage = "The data was successfully located";

        $this->get("/invoices/applications/{$applicationId}/company");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertTrue($responseArray['success']);
        $this->assertContains($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationCompanyWithInvalidNonNumericApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = "A";
        $expectedError = 'A valid application ID must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get("/invoices/applications/{$applicationId}/company");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationCompanyWithInvalidSymbolicApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = "@";
        $expectedError = 'A valid application ID must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get("/invoices/applications/{$applicationId}/company");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationCompanyWithNonExistentApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = 999;
        $expectedError = "Requested application has no company";
        $expectedMessage = "The requested data could not be located";

        $query = TableRegistry::get('InvoiceComponent.Applications')->find('all')
            ->contain('Companies.Addresses')->where(['Applications.id' => $applicationId])
            ->andWhere(['cancelled' => 0]);

        $expected = $query->enableHydration(false)->toArray();

        $this->get("/invoices/applications/{$applicationId}/company");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(404);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationMetadataWithValidApplicationIdReturnsSuccessfulResponseAndMatchingRecord()
    {
        $applicationId = 1;
        $expectedMessage = "The data was successfully located";

        $this->get("/invoices/applications/{$applicationId}/data");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertTrue($responseArray['success']);
        $this->assertContains($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationMetadataWithInvalidNonNumericApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = "A";
        $expectedError = 'A valid application ID must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get("/invoices/applications/{$applicationId}/data");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationMetadataWithInvalidSymbolicApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = "@";
        $expectedError = 'A valid application ID must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get("/invoices/applications/{$applicationId}/data");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationMetadataWithNonExistentApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = 999;
        $expectedError = "Requested application has no invoice";
        $expectedMessage = "The requested data could not be located";

        $query = TableRegistry::get('InvoiceComponent.Invoices')->find('all')
            ->contain('Applications.Confirmations')->where(['application_id' => $applicationId])
            ->andWhere(['cancelled' => 0]);

        $expected = $query->enableHydration(false)->toArray();

        $this->get("/invoices/applications/{$applicationId}/data");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(404);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationLinesWithValidApplicationIdReturnsSuccessfulResponseAndMatchingRecord()
    {
        $applicationId = 1;
        $expectedMessage = "The data was successfully located";

        $this->get("/invoices/applications/{$applicationId}/lines");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertTrue($responseArray['success']);
        $this->assertContains($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationLinesWithInvalidNonNumericApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = "A";
        $expectedError = 'A valid application ID must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get("/invoices/applications/{$applicationId}/lines");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationLinesWithInvalidSymbolicApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = "@";
        $expectedError = 'A valid application ID must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get("/invoices/applications/{$applicationId}/lines");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationLinesWithNonExistentApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = 999;
        $expectedError = "Requested application has no lines";
        $expectedMessage = "The requested data could not be located";

        $query = TableRegistry::get('InvoiceComponent.Invoices')->find('all')
            ->contain(['Applications' => ['Confirmations', 'ApplicationLines.Furnishings.Rooms', 'Companies.Addresses', 'Customers.Addresses']])
            ->where(['application_id' => $applicationId])
            ->andWhere(['cancelled' => 0]);

        $expected = $query->enableHydration(false)->toArray();

        $this->get("/invoices/applications/{$applicationId}/lines");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(404);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testProduceInvoiceWithValidApplicationIdReturnsSuccessfulResponse()
    {
        $applicationId = 1;

        $this->get("/invoices/applications/{$applicationId}/");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(201);
    }

    public function testProduceInvoiceWithInvalidRequestMethodReturnsUnsuccessfulResponse()
    {
        $applicationId = 1;
        $expectedMessage = "The request was denied due to invalid method";
        $expectedError = "HTTP Method disabled for endpoint: Use GET";

        $this->put("/invoices/applications/{$applicationId}/", []);
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(405);        
        $this->assertFalse($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertContains($expectedError, $responseArray["error"]);
    }

    public function testProduceInvoiceWithWithInvalidNonNumericApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = "A";
        $expectedError = 'A valid application ID must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get("/invoices/applications/{$applicationId}/");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testProduceInvoiceWithWithInvalidNonExistentApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = 999;
        $expectedError = "Requested application has no data";
        $expectedMessage = "The file could not be retrieved";

        $this->get("/invoices/applications/{$applicationId}/");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(404);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testProduceInvoiceWithWithInvalidSymbolicApplicationIdReturnsUnsuccessfulResponse()
    {
        $applicationId = "@";
        $expectedError = 'A valid application ID must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get("/invoices/applications/{$applicationId}/");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }
}

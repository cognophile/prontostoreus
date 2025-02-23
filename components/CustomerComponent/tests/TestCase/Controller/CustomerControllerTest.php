<?php

namespace CustomerComponent\Test\TestCase\Controller;

use Cake\Http\Response;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use CustomerComponent\Controller\CustomersController;

class CustomersControllerTest extends IntegrationTestCase
{
    public $fixtures = [
        'plugin.customer_component.customers',
        'plugin.customer_component.addresses'
    ];

    public function validCustomerProvider() 
    {
        return 
        [
            "title" => "Mr", 
            "dob" => "1975/05/05",
            "firstname" => "Dave", 
            "surname" => "Smith", 
            "email" => "example-email@domain.com", 
            "telephone" => "01982874563", 
            "addresses" => [
                "line_one" => "9 Southend", 
                "line_two" => "", 
                "town" => "Cambridge", 
                "county" => "Cambridgeshire", 
                "postcode" => "CB5-9CX"
            ]
        ];
    }

    public function invalidCustomerDobProvider() 
    {
        return 
        [
            "title" => "Mr", 
            "dob" => "01/01/1970",
            "firstname" => "Dave", 
            "surname" => "Smith", 
            "email" => "example-email@domain.com", 
            "telephone" => "01982874563", 
            "addresses" => [
                "line_one" => "9 Southend", 
                "line_two" => "", 
                "town" => "Cambridge", 
                "county" => "Cambridgeshire", 
                "postcode" => "CB5-9CX"
            ]
        ];
    }

    public function invalidCustomerTelephoneProvider() 
    {
        return 
        [
            "title" => "Mr", 
            "dob" => "1970/01/01",
            "firstname" => "Dave", 
            "surname" => "Smith", 
            "email" => "example-email@domain.com", 
            "telephone" => "0198ABC4563", 
            "addresses" => [
                "line_one" => "9 Southend", 
                "line_two" => "", 
                "town" => "Cambridge", 
                "county" => "Cambridgeshire", 
                "postcode" => "CB5-9CX"
            ]
        ];
    }

    public function testGetCustomersComponentStatusRouteWhereResponseIsSuccessful()
    {
        $this->get(Configure::read('Api.Scope') . 'customers');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    public function testMultipleGetCustomersComponentStatusRouteInSuccessionIsStable()
    {
        $this->get(Configure::read('Api.Scope') . 'customers');
        $this->assertResponseOk();

        $this->get(Configure::read('Api.Scope') . 'customers');
        $this->assertResponseOk();
    }

    public function testGetCustomersComponentStatusRouteResponseIsJsonFormat()
    {
        $this->get(Configure::read('Api.Scope') . 'customers');
        $this->assertContentType('application/json');
    }

    public function testGetCustomersComponentStatusRouteResponseIsNotEmpty()
    {
        $this->get(Configure::read('Api.Scope') . 'customers');
        $this->assertResponseNotEmpty();
    }

    public function testGetCustomersComponentStatusRouteResponseStructure()
    {
        $this->get(Configure::read('Api.Scope') . 'customers');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('url', $responseArray);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertArrayHasKey('links', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
    }

    public function testPostToAddCustomerWithValidCustomerAndAddressReturnsSuccessfulResponse()
    {
        $customer = $this->validCustomerProvider();
        $expectedMessage = "The data was successfully added";

        $this->post(Configure::read('Api.Scope') . 'customers/add', $customer);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseSuccess();        
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertTrue($responseArray["success"]);
    }

    public function testPostToAddCustomerWithEmptyCustomerDataReturnsUnsuccessfulResponse()
    {
        $customer = [];
        $expectedMessage = "An error occurred when parsing the payload which appeared empty";

        $this->post(Configure::read('Api.Scope') . 'customers/add', $customer);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseCode(400);      
        $this->assertContains($expectedMessage, $responseArray["message"]);
    }

    public function testPostToAddCustomerWithInvalidCustomerDataReturnsUnsuccessfulResponse()
    {
        $customer = $this->invalidCustomerDobProvider();
        $expectedMessage = "An error occurred when storing the data";
        $expectedError = ["dob" => ["date" => "The provided value is invalid"]];

        $this->post(Configure::read('Api.Scope') . 'customers/add', $customer);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseCode(400);        
        $this->assertFalse($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertEquals($expectedError, $responseArray["error"]);
    }

    public function testPostToAddCustomerWithInvalidCustomerTelephoneDataFormatReturnsUnsuccessfulResponse()
    {
        $customer = $this->invalidCustomerTelephoneProvider();
        $expectedMessage = "An error occurred when storing the data";
        $expectedError = ["telephone" => ["numeric" => "The provided value is invalid"]];

        $this->post(Configure::read('Api.Scope') . 'customers/add', $customer);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseCode(400);        
        $this->assertFalse($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertEquals($expectedError, $responseArray["error"]);
    }

    public function testPutAddCustomerWithValidCustomerDataReturnsErrorResponse()
    {
        $customer = $this->validCustomerProvider();
        $expectedMessage = "An error occurred when storing the data";
        $expectedError = "HTTP Method disabled for endpoint: Use POST";

        $this->put(Configure::read('Api.Scope') . 'customers/add', $customer);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseCode(405);        
        $this->assertFalse($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertContains($expectedError, $responseArray["error"]);
    }

    public function testPostToAddCustomerWithValidCustomerDuplicateEmailReturnsSuccessfulResponse()
    {
        $customer = $this->validCustomerProvider();
        $duplicateCustomer = $this->validCustomerProvider();
        $expectedMessage = "The data was successfully added";

        $this->post(Configure::read('Api.Scope') . 'customers/add', $customer);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->post(Configure::read('Api.Scope') . 'customers/add', $duplicateCustomer);
        $duplicateResponseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();        
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertTrue($responseArray["success"]);
        
        $this->assertResponseSuccess();        
        $this->assertContains($expectedMessage, $duplicateResponseArray["message"]);
        $this->assertTrue($duplicateResponseArray["success"]);
    }
}

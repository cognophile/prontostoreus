<?php

namespace CustomerComponent\Test\TestCase\Controller;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use CustomerComponent\Controller\ComponentController;

class CustomerControllerTest extends IntegrationTestCase
{
    public $fixtures = [

    ];

    public function testGetCustomerComponentStatusRouteWhereResponseIsSuccessful()
    {
        $this->get('/customer');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    public function testMultipleGetCustomerComponentStatusRouteInSuccessionIsStable()
    {
        $this->get('/customer');
        $this->assertResponseOk();

        $this->get('/customer');
        $this->assertResponseOk();
    }

    public function testGetCustomerComponentStatusRouteResponseIsJsonFormat()
    {
        $this->get('/customer');
        $this->assertContentType('application/json');
    }

    public function testGetCustomerComponentStatusRouteResponseIsNotEmpty()
    {
        $this->get('/customer');
        $this->assertResponseNotEmpty();
    }

    public function testGetCustomerComponentStatusRouteResponseStructure()
    {
        $this->get('/customer');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
    }
}

<?php

namespace CustomerComponent\Test\TestCase\Controller;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use CustomerComponent\Controller\CustomersController;

class CustomersControllerTest extends IntegrationTestCase
{
    public $fixtures = [

    ];

    public function testGetCustomersComponentStatusRouteWhereResponseIsSuccessful()
    {
        $this->get('/customer');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    public function testMultipleGetCustomersComponentStatusRouteInSuccessionIsStable()
    {
        $this->get('/customer');
        $this->assertResponseOk();

        $this->get('/customer');
        $this->assertResponseOk();
    }

    public function testGetCustomersComponentStatusRouteResponseIsJsonFormat()
    {
        $this->get('/customer');
        $this->assertContentType('application/json');
    }

    public function testGetCustomersComponentStatusRouteResponseIsNotEmpty()
    {
        $this->get('/customer');
        $this->assertResponseNotEmpty();
    }

    public function testGetCustomersComponentStatusRouteResponseStructure()
    {
        $this->get('/customer');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
    }
}

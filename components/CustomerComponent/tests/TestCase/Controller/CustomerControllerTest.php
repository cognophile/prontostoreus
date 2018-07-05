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
        $this->get('/customers');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    public function testMultipleGetCustomersComponentStatusRouteInSuccessionIsStable()
    {
        $this->get('/customers');
        $this->assertResponseOk();

        $this->get('/customers');
        $this->assertResponseOk();
    }

    public function testGetCustomersComponentStatusRouteResponseIsJsonFormat()
    {
        $this->get('/customers');
        $this->assertContentType('application/json');
    }

    public function testGetCustomersComponentStatusRouteResponseIsNotEmpty()
    {
        $this->get('/customers');
        $this->assertResponseNotEmpty();
    }

    public function testGetCustomersComponentStatusRouteResponseStructure()
    {
        $this->get('/customers');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('url', $responseArray);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertArrayHasKey('links', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
    }
}

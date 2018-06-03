<?php

namespace CustomerDetailsComponent\Test\TestCase\Controller;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use CustomerDetailsComponent\Controller\ComponentController;

class ComponentControllerTest extends IntegrationTestCase
{
    public $fixtures = [

    ];

    public function testGetCustomerDetailsComponentStatusRouteWhereResponseIsSuccessful()
    {
        $this->get('/details');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    public function testMultipleGetCustomerDetailsComponentStatusRouteInSuccessionIsStable()
    {
        $this->get('/details');
        $this->assertResponseOk();

        $this->get('/details');
        $this->assertResponseOk();
    }

    public function testGetCustomerDetailsComponentStatusRouteResponseIsJsonFormat()
    {
        $this->get('/details');
        $this->assertContentType('application/json');
    }

    public function testGetCustomerDetailsComponentStatusRouteResponseIsNotEmpty()
    {
        $this->get('/details');
        $this->assertResponseNotEmpty();
    }

    public function testGetCustomerDetailsComponentStatusRouteResponseStructure()
    {
        $this->get('/details');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
    }
    
    public function testGetCWhereInvalidRouteGivenRaises5xxError()
    {
        $this->get('/details/lorem');

        $this->assertResponseCode(500);
    }
}

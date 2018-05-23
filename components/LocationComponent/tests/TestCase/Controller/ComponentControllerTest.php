<?php

namespace LocationLocationComponent\Test\TestCase\Controller;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use LocationComponent\Controller\ComponentController;

class ComponentControllerTest extends IntegrationTestCase
{
    public $fixtures = [

    ];

    public function testGetLocationComponentStatusRouteWhereResponseIsSuccessful()
    {
        $this->get('/locate');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    public function testMultipleGetLocationComponentStatusRouteInSuccessionIsStable()
    {
        $this->get('/locate');
        $this->assertResponseOk();

        $this->get('/locate');
        $this->assertResponseOk();
    }

    public function testGetLocationComponentStatusRouteResponseIsJsonFormat()
    {
        $this->get('/locate');
        $this->assertContentType('application/json');
    }

    public function testGetLocationComponentStatusRouteResponseIsNotEmpty()
    {
        $this->get('/locate');
        $this->assertResponseNotEmpty();
    }

    public function testGetLocationComponentStatusRouteResponseStructure()
    {
        $this->get('/locate');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
    }
    
    public function testGetNonExistantSubRouteRaises4xxError()
    {
        $this->get('/locate/fake');

        $this->assertResponseError();
        $this->assertResponseCode(404);
    }
}

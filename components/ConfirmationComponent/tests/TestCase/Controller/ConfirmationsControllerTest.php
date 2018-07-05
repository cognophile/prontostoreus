<?php

namespace ConfirmationComponent\Test\TestCase\Controller;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use ConfirmationComponent\Controller\ConfirmationController;

class ConfirmationsControllerTest extends IntegrationTestCase
{
    public $fixtures = [

    ];

    public function testGetConfirmationComponentStatusRouteWhereResponseIsSuccessful()
    {
        $this->get('/confirmations');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    public function testMultipleGetConfirmationComponentStatusRouteInSuccessionIsStable()
    {
        $this->get('/confirmations');
        $this->assertResponseOk();

        $this->get('/confirmations');
        $this->assertResponseOk();
    }

    public function testGetConfirmationComponentStatusRouteResponseIsJsonFormat()
    {
        $this->get('/confirmations');
        $this->assertContentType('application/json');
    }

    public function testGetConfirmationComponentStatusRouteResponseIsNotEmpty()
    {
        $this->get('/confirmations');
        $this->assertResponseNotEmpty();
    }

    public function testGetConfirmationComponentStatusRouteResponseStructure()
    {
        $this->get('/confirmations');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('url', $responseArray);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertArrayHasKey('links', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
    }
}

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
        $this->get('/confirm');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    public function testMultipleGetConfirmationComponentStatusRouteInSuccessionIsStable()
    {
        $this->get('/confirm');
        $this->assertResponseOk();

        $this->get('/confirm');
        $this->assertResponseOk();
    }

    public function testGetConfirmationComponentStatusRouteResponseIsJsonFormat()
    {
        $this->get('/confirm');
        $this->assertContentType('application/json');
    }

    public function testGetConfirmationComponentStatusRouteResponseIsNotEmpty()
    {
        $this->get('/confirm');
        $this->assertResponseNotEmpty();
    }

    public function testGetConfirmationComponentStatusRouteResponseStructure()
    {
        $this->get('/confirm');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
    }
}

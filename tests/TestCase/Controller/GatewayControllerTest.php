<?php

namespace Prontostoreus\Api\Test\TestCase\Controller;

use Prontostoreus\Api\Controller;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use Cake\View\Exception\MissingTemplateException;

class GatewayControllerTest extends IntegrationTestCase
{
    public function testGetBaseGatewayRouteResponseIsSuccessful()
    {
        $this->get('/');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }
    
    public function testMultipleGetBaseGatewayRouteInSuccessionIsStable()
    {
        $this->get('/');
        $this->assertResponseOk();

        $this->get('/');
        $this->assertResponseOk();
    }

    public function testGetBaseGatewayRouteResponseIsJsonFormat()
    {
        $this->get('/');
        $this->assertContentType('application/json');
    }

    public function testGetBaseGatewayRouteResponseIsNotEmpty()
    {
        $this->get('/');
        $this->assertResponseNotEmpty();
    }

    public function testGetBaseGatewayRouteResponse()
    {
        $this->get('/');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('version', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
    }
    
    public function testGetNonExistantRouteRaises4xxError()
    {
        $this->get('/fake');

        $this->assertResponseError();
        $this->assertResponseCode(404);
    }
}

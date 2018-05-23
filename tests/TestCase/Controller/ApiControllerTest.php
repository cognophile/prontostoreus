<?php

namespace Prontostoreus\Api\Test\TestCase\Controller;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use Cake\View\Exception\MissingTemplateException;
use Prontostoreus\Api\Controller\ApiController;

class ApiControllerTest extends IntegrationTestCase
{
    public function testGetApiStatusRouteResponseIsSuccessful()
    {
        $this->get('/');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }
    
    public function testMultipleGetApiStatusRouteInSuccessionIsStable()
    {
        $this->get('/');
        $this->assertResponseOk();

        $this->get('/');
        $this->assertResponseOk();
    }

    public function testGetApiStatusRouteResponseIsJsonFormat()
    {
        $this->get('/');
        $this->assertContentType('application/json');
    }

    public function testGetApiStatusRouteResponseIsNotEmpty()
    {
        $this->get('/');
        $this->assertResponseNotEmpty();
    }

    public function testGetApiStatusRouteResponseStructure()
    {
        $this->get('/');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('version', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
    }
    
    public function testGetNonExistantSubRouteRaises4xxError()
    {
        $this->get('/fake');

        $this->assertResponseError();
        $this->assertResponseCode(404);
    }
}

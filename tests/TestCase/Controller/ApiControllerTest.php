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
        $this->get(Configure::read('Api.Scope'));
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }
    
    public function testMultipleGetApiStatusRouteInSuccessionIsStable()
    {
        $this->get(Configure::read('Api.Scope'));
        $this->assertResponseOk();

        $this->get(Configure::read('Api.Scope'));
        $this->assertResponseOk();
    }

    public function testGetApiStatusRouteResponseIsJsonFormat()
    {
        $this->get(Configure::read('Api.Scope'));
        $this->assertContentType('application/json');
    }

    public function testGetApiStatusRouteResponseIsNotEmpty()
    {
        $this->get(Configure::read('Api.Scope'));
        $this->assertResponseNotEmpty();
    }

    public function testGetApiStatusRouteResponseStructure()
    {
        $this->get(Configure::read('Api.Scope'));

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('version', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
    }
    
    public function testGetNonExistantSubRouteRaises4xxError()
    {
        $this->get('/api/v1//fake');

        $this->assertResponseError();
        $this->assertResponseCode(404);
    }
}

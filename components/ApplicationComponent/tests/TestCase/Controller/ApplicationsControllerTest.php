<?php

namespace ApplicationComponent\Test\TestCase\Controller;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use ApplicationComponent\Controller\ApplicationsController;

class ApplicationsControllerTest extends IntegrationTestCase
{
    public $fixtures = [

    ];

    public function testGetApplicationsComponentStatusRouteWhereResponseIsSuccessful()
    {
        $this->get('/applications');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    public function testMultipleGetApplicationsComponentStatusRouteInSuccessionIsStable()
    {
        $this->get('/applications');
        $this->assertResponseOk();

        $this->get('/applications');
        $this->assertResponseOk();
    }

    public function testGetApplicationsComponentStatusRouteResponseIsJsonFormat()
    {
        $this->get('/applications');
        $this->assertContentType('application/json');
    }

    public function testGetApplicationsComponentStatusRouteResponseIsNotEmpty()
    {
        $this->get('/applications');
        $this->assertResponseNotEmpty();
    }

    public function testGetApplicationsComponentStatusRouteResponseStructure()
    {
        $this->get('/applications');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('url', $responseArray);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertArrayHasKey('links', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
    }
}

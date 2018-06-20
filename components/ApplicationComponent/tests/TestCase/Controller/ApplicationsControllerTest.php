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
        $this->get('/apply');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    public function testMultipleGetApplicationsComponentStatusRouteInSuccessionIsStable()
    {
        $this->get('/apply');
        $this->assertResponseOk();

        $this->get('/apply');
        $this->assertResponseOk();
    }

    public function testGetApplicationsComponentStatusRouteResponseIsJsonFormat()
    {
        $this->get('/apply');
        $this->assertContentType('application/json');
    }

    public function testGetApplicationsComponentStatusRouteResponseIsNotEmpty()
    {
        $this->get('/apply');
        $this->assertResponseNotEmpty();
    }

    public function testGetApplicationsComponentStatusRouteResponseStructure()
    {
        $this->get('/apply');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
    }
}

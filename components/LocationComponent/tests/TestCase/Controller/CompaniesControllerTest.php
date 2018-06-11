<?php

namespace LocationComponent\Test\TestCase\Controller;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use LocationComponent\Controller\ComponentController;

class CompaniesControllerTest extends IntegrationTestCase
{
    public $fixtures = [
        'plugin.location_component.addresses',
        'plugin.location_component.companies'
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
        $this->assertArrayHasKey('links', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
    }
    
    public function testLocationComponentWhereInvalidPostcodeMissingHyphenGivenReturnsJsonError()
    {
        $expectedError = 'The postcode must conform to the following format: AreaDistrict-SectorUnit';
        $expectedMessage = 'The given URI argument was invalid.';

        $this->get('/locate/AB121DE');
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertContains($expectedError, $responseArray);
        $this->assertContains($expectedMessage, $responseArray);
    }

    public function testLocationComponentWhereInvalidMediumLengthPostcodeMissingHyphenGivenReturnsJsonError()
    {
        $expectedError = 'The postcode must conform to the following format: AreaDistrict-SectorUnit';
        $expectedMessage = 'The given URI argument was invalid.';

        $this->get('/locate/AB21DE');
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertContains($expectedError, $responseArray);
        $this->assertContains($expectedMessage, $responseArray);
    }

    public function testLocationComponentWhereValidFullLengthHyphenatedPostcodeGivenReturnsJsonSuccess()
    {
        $expectedMessage = 'The data was successfully located.';

        $this->get('/locate/AB12-1DE');        
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertContains($expectedMessage, $responseArray);
    }

    public function testLocationComponentWhereInvalidAlphanumericFormattedPostcodeGivenReturnsJsonError()
    {   
        $expectedError = 'The postcode must conform to the following format: AreaDistrict-SectorUnit';
        $expectedMessage = 'The given URI argument was invalid.';

        $this->get('/locate/12AB-D12');        
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertContains($expectedError, $responseArray);
        $this->assertContains($expectedMessage, $responseArray);
    }
}

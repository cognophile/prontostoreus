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
        $this->get('/locations');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    public function testMultipleGetLocationComponentStatusRouteInSuccessionIsStable()
    {
        $this->get('/locations');
        $this->assertResponseOk();

        $this->get('/locations');
        $this->assertResponseOk();
    }

    public function testGetLocationComponentStatusRouteResponseIsJsonFormat()
    {
        $this->get('/locations');
        $this->assertContentType('application/json');
    }

    public function testGetLocationComponentStatusRouteResponseIsNotEmpty()
    {
        $this->get('/locations');
        $this->assertResponseNotEmpty();
    }

    public function testGetLocationComponentStatusRouteResponseStructure()
    {
        $this->get('/locations');

        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('url', $responseArray);
        $this->assertArrayHasKey('error', $responseArray);
        $this->assertArrayHasKey('links', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
    }
    
    public function testLocationComponentWhereInvalidPostcodeMissingHyphenGivenReturnsJsonError()
    {
        $expectedMessage = 'The postcode must conform to the following format: AreaDistrict-SectorUnit';
        $expectedError = 'The given URI argument was invalid';

        $this->get('/locations/AB121DE');
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertContains($expectedError, $responseArray["error"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
    }

    public function testLocationComponentWhereInvalidMediumLengthPostcodeMissingHyphenGivenReturnsJsonError()
    {
        $expectedMessage = 'The postcode must conform to the following format: AreaDistrict-SectorUnit';
        $expectedError = 'The given URI argument was invalid';

        $this->get('/locations/AB21DE');
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertContains($expectedError, $responseArray["error"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
    }

    public function testLocationComponentWhereValidFullLengthHyphenatedPostcodeGivenReturnsJsonSuccess()
    {
        $expectedMessage = 'The data was successfully located';

        $this->get('/locations/AB12-1DE');        
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertContains($expectedMessage, $responseArray["message"]);
    }

    public function testLocationComponentWhereInvalidAlphanumericFormattedPostcodeGivenReturnsJsonError()
    {   
        $expectedMessage = 'The postcode must conform to the following format: AreaDistrict-SectorUnit';
        $expectedError = 'The given URI argument was invalid';

        $this->get('/locations/12AB-D12');        
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertContains($expectedError, $responseArray["error"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
    }
}

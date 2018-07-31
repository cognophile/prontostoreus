<?php

namespace ConfirmationComponent\Test\TestCase\Controller;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use ConfirmationComponent\Controller\ConfirmationController;

class ConfirmationsControllerTest extends IntegrationTestCase
{
    public $fixtures = [
        'plugin.confirmation_component.confirmations',
        'plugin.confirmation_component.applications'
    ];

       private function validConfirmationProvider($applicationId)
    {
        return 
        [
            'application_id' => $applicationId,
            'accepted' => true,
            'date_accepted' =>  "1970/01/01 13:00:00"
        ];
    }

    private function invalidAcceptedConfirmationProvider($applicationId)
    {
        return 
        [
            'application_id' => $applicationId,
            'accepted' => 9,
            'date_accepted' =>  "1970/01/01 13:00:00"
        ];
    }

    private function invalidDateConfirmationProvider($applicationId)
    {
        return 
        [
            'application_id' => $applicationId,
            'accepted' => true,
            'date_accepted' =>  "01/01/1970 13:00:00"
        ];
    }

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

    public function testPutConfirmationsComponentUpdateWithValidPayloadReturnsUnsuccessfulResponse()
    {   
        $applicationId = 1;
        $data = $this->validConfirmationProvider($applicationId);
        $expectedMessage = "An error occurred when editing the data";
        $expectedError = "HTTP Method disabled for endpoint: Use POST";

        $this->put("/confirmations/applications/{$applicationId}/update", $data);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseCode(405);        
        $this->assertFalse($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertContains($expectedError, $responseArray["error"]);
    }

    public function testPostConfirmationsComponentUpdateWithValidPayloadReturnsSuccessfulResponse()
    {   
        $applicationId = 1;
        $data = $this->validConfirmationProvider($applicationId);
        $expectedMessage = "The data was successfully edited";

        $this->post("/confirmations/applications/{$applicationId}/update", $data);
        $responseArray = json_decode($this->_response->getBody(), true);
                
        $this->assertResponseSuccess();        
        $this->assertTrue($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
    }

    public function testPostConfirmationsComponentUpdateWithNonExistentApplicationIdReturnsErrorResponse()
    {   
        $applicationId = 999;
        $data = $this->validConfirmationProvider($applicationId);
        $expectedMessage = "An error occurred when storing the data";
        $expectedError = "The requested record does not exist";

        $this->post("/confirmations/applications/{$applicationId}/update", $data);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseCode(400);        
        $this->assertFalse($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertContains($expectedError, $responseArray["error"]);
    }

    public function testPostConfirmationsComponentUpdateWithInvalidUriArgumentAsCharacterTypeReturnsInvalidTypeError()
    {   
        $applicationId = "A";
        $data = $this->validConfirmationProvider($applicationId);
        $expectedError = "A valid application ID must be provided";
        $expectedMessage = "The given URI argument was invalid";

        $this->post("/confirmations/applications/{$applicationId}/update", $data);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseCode(400);        
        $this->assertFalse($responseArray["success"]);
        $this->assertContains($expectedError, $responseArray["error"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
    }

    public function testPostConfirmationsComponentUpdateWithInvalidUriArgumentAsStringNumericTypeReturnsInvalidTypeError()
    {   
        $applicationId = "1";
        $data = $this->validConfirmationProvider($applicationId);
        $expectedMessage = "The data was successfully edited";

        $this->post("/confirmations/applications/{$applicationId}/update", $data);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseSuccess();        
        $this->assertTrue($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
    }

    public function testPostConfirmationComponentWithValidApplicationIdWithoutConfirmationRecordCreatesRecordReturnsSuccessfulResponse() 
    {
        $applicationId = 2;
        $data = $this->validConfirmationProvider($applicationId);
        $expectedMessage = "The data was successfully added";

        $this->post("/confirmations/applications/{$applicationId}/update", $data);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseSuccess();        
        $this->assertTrue($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
    }
}

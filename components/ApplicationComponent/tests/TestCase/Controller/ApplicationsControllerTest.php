<?php

namespace ApplicationComponent\Test\TestCase\Controller;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use ApplicationComponent\Controller\ApplicationsController;

class ApplicationsControllerTest extends IntegrationTestCase
{
    public $fixtures = [
        'plugin.application_component.applications',
        'plugin.application_component.company_furnishing_rates',
        'plugin.application_component.rooms',
        'plugin.application_component.furnishings',
        'plugin.application_component.application_lines'    
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

    public function testGetApplicationRoomsListWithValidRecordIdReturnsFullyPopulatedList()
    {
        $query = TableRegistry::get('ApplicationComponent.Rooms')->find('all');
        $expected = $query->enableHydration(false)->toArray();

        $this->get('/applications/room');
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationRoomsListEndpointWithValidRecordIdReturnsSingleRecord()
    {
        $query = TableRegistry::get('ApplicationComponent.Rooms')->get(1);
        $expected = $query->toArray();

        $this->get('/applications/room/1');
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationRoomsListEndpointWithZeroRecordIdReturnsFullyPopulatedList()
    {
        $roomId = 0;
        $query = TableRegistry::get('ApplicationComponent.Rooms')->find('all');
        $expected = $query->enableHydration(false)->toArray();

        $this->get("/applications/room/{$roomId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationRoomFurnishingsEndpointReturnsFullyPopulatedListOfFurnitureForSelectedRoom()
    {
        $roomId = 1;
        $query = TableRegistry::get('ApplicationComponent.Furnishings')->find('all')->where(['room_id' => $roomId]);
        $expected = $query->enableHydration(false)->toArray();

        $this->get("/applications/room/{$roomId}/furnishing");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationRoomEndpointWithNonExistentRecordIdRespondsException()
    {
        $roomId = 999;
        $expectedError = "Record not found in table \"rooms\"";
        $expectedMessage = "The requested data could not be located";

        $this->get("/applications/room/{$roomId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(404);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }


    public function testGetApplicationRoomsListEndpointWithNonExistentRoomAndValidFurnishingIdReturnsException()
    {
        $roomId = 999; $furnishingId = 1;
        $expectedError = "Requested furnishing not associated with requested room";
        $expectedMessage = "The requested data could not be located";

        $this->get("/applications/room/{$roomId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(404);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationRoomFurnishingsEndpointReturnsSingleFurnishingRecordForSelectedRoom()
    {
        $roomId = 1; $furnishingId = 1;
        $query = TableRegistry::get('ApplicationComponent.Furnishings')->get($furnishingId);
        $expected = $query->toArray();

        $this->get("/applications/room/{$roomId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationItemCostByCompanyEndpointReturnsSingleFurnishingRecordWithRatesForGivenCompany()
    {
        $companyId = 1; $furnishingId = 1;
        $table = TableRegistry::get('ApplicationComponent.CompanyFurnishingRates')->find();
        
        $query = $table->select(['company_id', 'furnishing_id', 'cost'])
            ->where(['company_id' => $companyId])
            ->andWhere(['furnishing_id' => $furnishingId])
            ->andWhere(['deleted' => 0]);
        
        $expected = $query->enableHydration(false)->toArray();

        $this->get("/applications/company/{$companyId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationItemCostByCompanyEndpointReturnsUnsuccessfulResponseWithInvalidFurnishingId()
    {
        $expectedError = "Requested company or furnishing ID does not exist";
        $companyId = 1; $furnishingId = 999;

        $this->get("/applications/company/{$companyId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(404);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
    }
}

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
        // TODO: Remove reliance upon other components by baking these classes into this component (requires updates to relationship definitions on existing component models)
        'plugin.application_component.applications',
        'plugin.application_component.company_furnishing_rates',
        'plugin.application_component.rooms',
        'plugin.application_component.furnishings',
        'plugin.application_component.application_lines',
        'plugin.application_component.customers',
        'plugin.application_component.companies'
    ];

    private function validApplicationProvider($lines)
    {
        return 
        [
            "customer_id" => 1,
            "company_id" => 1,
            "delivery" => 0,
            "start_date" => "2018-08-01",
            "end_date" => "2018-08-31",
            "total_cost" => "50.50",
            "application_lines" => $lines
        ];
    }

    private function validLinesProvider()
    {
        return 
        [
            "line_one" => [
                "furnishing_id" => 1,
                "quantity" => 1,
                "line_cost" => "10.50"
            ],
            "line_two" => [
                "furnishing_id" => 2,
                "quantity" => 2,
                "line_cost" => "40.00"
            ]
        ];
    }

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

    public function testGetApplicationRoomFurnishingsEndpointWithNonExistentRoomIdRespondsError()
    {
        $roomId = 999;
        $expectedError = "Requested room does not exist";
        $expectedMessage = "The requested data could not be located";

        $query = TableRegistry::get('ApplicationComponent.Furnishings')->find('all')->where(['room_id' => $roomId]);
        $expected = $query->enableHydration(false)->toArray();

        $this->get("/applications/room/{$roomId}/furnishing");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(404);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
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


    public function testGetApplicationRoomsListEndpointWithNonExistentRoomAndValidFurnishingIdReturnsError()
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

    public function testPostApplicationWithoutLinesDataReturnsErrorResponse()
    {
        $data = $this->validApplicationProvider([]);
        $expectedMessage = "An error occurred when storing the data";
        $expectedError = "Cannot create an application without furniture lines data";

        $this->post('/applications/add', $data);
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(422);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testPostApplicationWithValidDataReturnsSuccessfulResponse()
    {
        $data = $this->validApplicationProvider($this->validLinesProvider());
        $expectedMessage = "The data was successfully added";

        $this->post('/applications/add', $data);
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();        
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertTrue($responseArray["success"]);
    }

    public function testPutAddApplicaitonWithValidDataReturnsErrorResponse()
    {
        $data = $this->validApplicationProvider($this->validLinesProvider());
        $expectedMessage = "An error occurred when storing the data";
        $expectedError = "HTTP Method disabled for endpoint: Use POST";

        $this->put('/applications/add', $data);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseCode(405);        
        $this->assertFalse($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertContains($expectedError, $responseArray["error"]);
    }
}

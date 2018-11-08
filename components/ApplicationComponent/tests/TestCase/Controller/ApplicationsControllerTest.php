<?php

namespace ApplicationComponent\Test\TestCase\Controller;

use Cake\Http\Response;
use Cake\Core\Configure;
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

    private function validAddApplicationProvider($lines)
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

    private function validUpdateApplicationProvider($lines)
    {
        return 
        [
            "id" => 1,
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
        $this->get(Configure::read('Api.Scope') . 'applications');
        
        $this->assertResponseOk();
        $this->assertResponseCode(200);
    }

    public function testMultipleGetApplicationsComponentStatusRouteInSuccessionIsStable()
    {
        $this->get(Configure::read('Api.Scope') . 'applications');
        $this->assertResponseOk();

        $this->get(Configure::read('Api.Scope') . 'applications');
        $this->assertResponseOk();
    }

    public function testGetApplicationsComponentStatusRouteResponseIsJsonFormat()
    {
        $this->get(Configure::read('Api.Scope') . 'applications');
        $this->assertContentType('application/json');
    }

    public function testGetApplicationsComponentStatusRouteResponseIsNotEmpty()
    {
        $this->get(Configure::read('Api.Scope') . 'applications');
        $this->assertResponseNotEmpty();
    }

    public function testGetApplicationsComponentStatusRouteResponseStructure()
    {
        $this->get(Configure::read('Api.Scope') . 'applications');

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

        $this->get(Configure::read('Api.Scope') . 'applications/room');
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationRoomsListEndpointWithValidRecordIdReturnsSingleRecord()
    {
        $query = TableRegistry::get('ApplicationComponent.Rooms')->get(1);
        $expected = $query->toArray();

        $this->get(Configure::read('Api.Scope') . 'applications/room/1');
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationRoomsListEndpointWithZeroRecordIdReturnsFullyPopulatedList()
    {
        $roomId = 0;
        $query = TableRegistry::get('ApplicationComponent.Rooms')->find('all');
        $expected = $query->enableHydration(false)->toArray();

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationRoomFurnishingsEndpointReturnsFullyPopulatedListOfFurnitureForSelectedRoom()
    {
        $roomId = 1;
        $query = TableRegistry::get('ApplicationComponent.Furnishings')->find('all')->where(['room_id' => $roomId]);
        $expected = $query->enableHydration(false)->toArray();

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}/furnishing");
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

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}/furnishing");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(404);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationRoomFurnishingsWithInvalidUriArgumentAsCharacterTypeReturnsInvalidArgumentError()
    {
        $roomId = "A";
        $expectedError = 'A valid room ID must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}/furnishing");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationRoomFurnishingsWithValidUriArgumentAsNumericStringTypeReturnsSuccessfulResponse()
    {
        $roomId = "1";
        $expectedMessage = "The data was successfully located";

        $query = TableRegistry::get('ApplicationComponent.Furnishings')->find('all')->where(['room_id' => $roomId]);
        $expected = $query->enableHydration(false)->toArray();

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}/furnishing");
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseSuccess();        
        $this->assertTrue($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationRoomEndpointWithNonExistentRecordIdRespondsException()
    {
        $roomId = 999;
        $expectedError = "Record not found in table \"rooms\"";
        $expectedMessage = "An unknown error occurred during the request (see error for details)";

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(404);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationRoomEndpointWithInvalidUriArgumentAsCharacterTypeReturnsInvalidArgumentError()
    {
        $roomId = "A";
        $expectedError = 'A valid room ID must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationRoomEndpointWithValidUriArgumentAsNumericStringTypeReturnsSuccessfulResponse()
    {
        $roomId = "1";
        $expectedMessage = "The data was successfully located";

        $query = TableRegistry::get('ApplicationComponent.Rooms')->find()->where(['id' => $roomId]);
        $expected = $query->enableHydration(false)->toArray();

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}");
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseSuccess();        
        $this->assertTrue($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertEquals($expected[0], $responseArray['data']);
    }

    public function testGetApplicationRoomFurnishingsEndpointWithNonExistentRoomAndValidFurnishingIdReturnsError()
    {
        $roomId = 999; $furnishingId = 1;
        $expectedError = "Requested furnishing not associated with requested room";
        $expectedMessage = "The requested data could not be located";

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}/furnishing/{$furnishingId}");
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

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationRoomFurnishingEndpointWithInvalidUriRoomIdArgumentAsCharacterTypeReturnsInvalidArgumentError()
    {
        $roomId = 'A'; $furnishingId = 1;
        $expectedError = 'Both valid room and furnishing IDs must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationRoomFurnishingEndpointWithInvalidUriFurnishingIdArgumentAsCharacterTypeReturnsInvalidArgumentError()
    {
        $roomId = 1; $furnishingId = 'A';
        $expectedError = 'Both valid room and furnishing IDs must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationRoomFurnishingsEndpointWithValidRoomIdUriArgumentAsNumericStringTypeReturnsSuccessfulResponse()
    {
        $roomId = "1"; $furnishingId = 1;
        $expectedMessage = "The data was successfully located";

        $query = TableRegistry::get('ApplicationComponent.Furnishings')->find()->where(['room_id' => $roomId]);
        $expected = $query->enableHydration(false)->toArray();

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseSuccess();        
        $this->assertTrue($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertEquals($expected[0], $responseArray['data']);
    }

    public function testGetApplicationRoomFurnishingsEndpointWithValidRoomIdAndFurnishingIdUriArgumentAsNumericStringTypeReturnsSuccessfulResponse()
    {
        $roomId = "1"; $furnishingId = "1";
        $expectedMessage = "The data was successfully located";

        $query = TableRegistry::get('ApplicationComponent.Furnishings')->find()->where(['room_id' => $roomId]);
        $expected = $query->enableHydration(false)->toArray();

        $this->get(Configure::read('Api.Scope') . "applications/room/{$roomId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseSuccess();        
        $this->assertTrue($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertEquals($expected[0], $responseArray['data']);
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

        $this->get(Configure::read('Api.Scope') . "applications/company/{$companyId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationItemCostByCompanyEndpointReturnsUnsuccessfulResponseWithInvalidFurnishingId()
    {
        $companyId = 1; $furnishingId = 999;
        $expectedError = "Requested company or furnishing ID does not exist";

        $this->get(Configure::read('Api.Scope') . "applications/company/{$companyId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(404);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
    }

    public function testGetApplicationItemCostByCompanyEndpointWithInvalidUriRoomIdArgumentAsCharacterTypeReturnsInvalidArgumentError()
    {
        $companyId = 'A'; $furnishingId = 1;
        $expectedError = 'Both valid company and furnishing IDs must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get(Configure::read('Api.Scope') . "applications/company/{$companyId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationItemCostByCompanyEndpointWithInvalidUriFurnishingIdArgumentAsCharacterTypeReturnsInvalidArgumentError()
    {
        $companyId = 1; $furnishingId = 'A';
        $expectedError = 'Both valid company and furnishing IDs must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->get(Configure::read('Api.Scope') . "applications/company/{$companyId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(400);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testGetApplicationItemCostByCompanyEndpointWithValidRoomIdUriArgumentAsNumericStringTypeReturnsSuccessfulResponse()
    {
        $companyId = "1"; $furnishingId = 1;
        $expectedMessage = "The data was successfully located";

        $table = TableRegistry::get('ApplicationComponent.CompanyFurnishingRates')->find();
        $query = $table->select(['company_id', 'furnishing_id', 'cost'])
            ->where(['company_id' => $companyId])
            ->andWhere(['furnishing_id' => $furnishingId])
            ->andWhere(['deleted' => 0]);
        
        $expected = $query->enableHydration(false)->toArray();

        $this->get(Configure::read('Api.Scope') . "applications/company/{$companyId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseSuccess();        
        $this->assertTrue($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testGetApplicationItemCostByCompanyEndpointWithValidRoomIdAndFurnishingIdUriArgumentAsNumericStringTypeReturnsSuccessfulResponse()
    {
        $companyId = "1"; $furnishingId = "1";
        $expectedMessage = "The data was successfully located";
        
        $table = TableRegistry::get('ApplicationComponent.CompanyFurnishingRates')->find();
        $query = $table->select(['company_id', 'furnishing_id', 'cost'])
            ->where(['company_id' => $companyId])
            ->andWhere(['furnishing_id' => $furnishingId])
            ->andWhere(['deleted' => 0]);
        
        $expected = $query->enableHydration(false)->toArray();

        $this->get(Configure::read('Api.Scope') . "applications/company/{$companyId}/furnishing/{$furnishingId}");
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseSuccess();        
        $this->assertTrue($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertEquals($expected, $responseArray['data']);
    }

    public function testPostApplicationWithoutLinesDataReturnsErrorResponse()
    {
        $data = $this->validAddApplicationProvider([]);
        $expectedMessage = "An error occurred when storing the data";
        $expectedError = "Cannot create an application without furniture lines data";

        $this->post(Configure::read('Api.Scope') . 'applications/add', $data);
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseCode(422);
        $this->assertFalse($responseArray['success']);
        $this->assertEquals($expectedError, $responseArray['error']);
        $this->assertEquals($expectedMessage, $responseArray['message']);
    }

    public function testPostApplicationWithValidDataReturnsSuccessfulResponse()
    {
        $data = $this->validAddApplicationProvider($this->validLinesProvider());
        $expectedMessage = "The data was successfully added";

        $this->post(Configure::read('Api.Scope') . 'applications/add', $data);
        $responseArray = json_decode($this->_response->getBody(), true);

        $this->assertResponseSuccess();        
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertTrue($responseArray["success"]);
    }

    public function testPutAddApplicationWithValidDataReturnsErrorResponse()
    {
        $data = $this->validAddApplicationProvider($this->validLinesProvider());
        $expectedMessage = "An error occurred when storing the data";
        $expectedError = "HTTP Method disabled for endpoint: Use POST";

        $this->put(Configure::read('Api.Scope') . 'applications/add', $data);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseCode(405);        
        $this->assertFalse($responseArray["success"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
        $this->assertContains($expectedError, $responseArray["error"]);
    }

    public function testPostApplicationComponentUpdateWithInvalidUriArgumentAsCharacterTypeReturnsInvalidTypeError()
    {   
        $applicationId = "A";
        $data = $this->validAddApplicationProvider($this->validLinesProvider());
        $expectedError = 'A valid application ID must be provided';
        $expectedMessage = 'The given URI argument was invalid';

        $this->post(Configure::read('Api.Scope') . "applications/{$applicationId}/edit", $data);
        $responseArray = json_decode($this->_response->getBody(), true);
        
        $this->assertResponseCode(400);        
        $this->assertFalse($responseArray["success"]);
        $this->assertContains($expectedError, $responseArray["error"]);
        $this->assertContains($expectedMessage, $responseArray["message"]);
    }
}

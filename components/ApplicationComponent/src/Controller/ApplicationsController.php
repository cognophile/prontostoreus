<?php
namespace ApplicationComponent\Controller;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\MethodNotAllowedException;

use Prontostoreus\Api\Controller\AbstractApiController;
use ApplicationComponent\Utility\TypeChecker\TypeChecker;

/**
 * Applications Controller
 */
class ApplicationsController extends AbstractApiController
{
    public function initialize() 
    {
        parent::initialize();        
    }

    public function status()
    {
        $message = $this->messageHandler->retrieve("General", "RouteAlive");
        $this->respondSuccess([], 200, "Application base: {$message}", Configure::read('Api.Routes.Applications'));
    }

    public function add() 
    {
        $data = $this->request->getData();

        if (empty($data['application_lines'])) {
            $this->respondError('Cannot create an application without furniture lines data', 422,
                $this->messageHandler->retrieve("Error", "UnsuccessfulAdd"));
            return;
        }

        return $this->universalAdd($this->Applications);
    }

    public function edit($applicationId) 
    {
        if (!$applicationId || !TypeChecker::isNumeric($applicationId)) {
            try {
                throw new BadRequestException('A valid application ID must be provided');
            }
            catch (BadRequestException $ex) {
                $this->respondException($ex, $this->messageHandler->retrieve("Error", "InvalidArgument"));
                return;
            }
        }
        
        return $this->universalEdit($this->Applications, $applicationId);
    }
}

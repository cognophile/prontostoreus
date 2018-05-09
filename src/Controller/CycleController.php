<?php

namespace Prontostoreus\Api\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

class CycleController extends Controller
{
    use CycleHydrationTrait;

    /**
     * Initialization hook method for common initialization code like loading components.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Paginator');

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    protected function add() 
    {
    }

    protected function view() 
    {
    }

    protected function edit() 
    {
    }

    protected function remove() 
    {
    }
}

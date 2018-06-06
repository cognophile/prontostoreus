<?php

namespace Prontostoreus\Api\Controller;

use Cake\Event\Event;
use Cake\Filesystem\Folder;

trait CycleHydrationTrait
{
    public function beforeRender(Event $event)
    {
        // Ensure we render only JSON responses
        $this->RequestHandler->renderAs($this, 'json');

        $this->setCorsHeaders();        
        $this->set('_serialize', true);
    }

    public function beforeFilter(Event $event)
    {
        if ($this->request->is('options')) {
            $this->setCorsHeaders();
            return $this->response;
        }
    }

    public function respondSuccess(array $data, string $message = "", string $error = "")
    {
        $response = [
            'message' => $message,
            'success' => true,
            'error' => $error,
            'data' => $data
        ];
    
        $this->set($response);
    }

    public function respondError(array $error, string $message = "", array $data = [])
    {
        $response = [
            'message' => $message,
            'success' => false,
            'error' => $error,
            'data' => $data
        ];

        $this->set($response);
    }

    private function setCorsHeaders() 
    {
        // For development purposes only
        $this->response->cors($this->request)
            ->allowOrigin(['http://localhost:8080/'])
            ->allowMethods(['*'])
            ->build();

        $this->response = $this->response->withHeader('Access-Control-Allow-Origin','*');
    }
}
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
            'error' => "",
            'data' => $data
        ];
    
        $this->set($response);
    }

    public function respondError(string $error, string $message = "", array $data = [])
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
        $this->response->cors($this->request)
            ->allowOrigin(['http://localhost:8080/'])
            ->allowMethods(['*'])
            ->allowHeaders(['Access-Control-Allow-Origin'])
            ->build();

        $this->response->header('Access-Control-Allow-Origin','*');
    }
}
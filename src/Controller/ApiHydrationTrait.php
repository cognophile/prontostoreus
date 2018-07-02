<?php

namespace Prontostoreus\Api\Controller;

use Cake\Event\Event;
use Cake\Filesystem\Folder;

trait ApiHydrationTrait
{
    public function beforeRender(Event $event)
    {
        // Render responses in JSON
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

    /**
     * Respond to the requestor indicating success
     * @param string|array $data
     * @param string $message
     * @param array $links
     * @param string $error
     * @return void
     */
    public function respondSuccess($data, string $message = "", array $links = [], string $error = "")
    {
        $response = [
            'message' => $message,
            'success' => true,
            'error' => $error,
            'links' => $links,
            'data' => $data
        ];
    
        $this->set($response);
    }

    /**
     * Respond to the requestor indicating failure or error
     * @param string|array $error
     * @param string $message
     * @param array $links
     * @param string $data
     * @return void
     */
    public function respondError($error, string $message = "", array $links = [], array $data = [])
    {
        $response = [
            'message' => $message,
            'success' => false,
            'error' => $error,
            'links' => $links,
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
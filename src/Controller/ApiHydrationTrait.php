<?php

namespace Prontostoreus\Api\Controller;

use Cake\Event\Event;
use Cake\Filesystem\Folder;
use Cake\Routing\Router;

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
     * 
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
            'url' => Router::url($this->here, true),
            'error' => $error,
            'links' => $links,
            'data' => $data
        ];
    
        $this->set($response);
    }

    /**
     * Respond to the requestor indicating failure or error
     * 
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
            'url' => Router::url($this->here, true),
            'error' => $error,
            'links' => $links,
            'data' => $data
        ];

        $this->set($response);
    }

    /**
     * Response to the request with a file
     *
     * @param string $location Path to file
     * @param string $filename Output name of file
     * @param boolean $download Force download of file
     * @return void
     */
    public function respondFile($location, $filename, $download = true)
    {
        $this->response = $this->response->withStatus(201);
        $this->response->header('Access-Control-Allow-Origin','*');
        $this->response = $this->response->withFile($location, ['name' => $filename, 'download' => $download]);
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
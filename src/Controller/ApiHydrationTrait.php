<?php

namespace Prontostoreus\Api\Controller;

use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Routing\Router;
use Cake\Log\Log;
use Cake\Http\Exception\MethodNotAllowedException;

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
     * @param string $error
     * @return void
     */
    protected function respondSuccess($data, int $code, string $message = "", array $links = [], string $error = ""): void
    {
        $componentName = $this->replaceString("Component", "s", $this->plugin);
        $hyperlinkedComponent = Configure::read("Api.Hyperlinks.ComponentRelationships.{$componentName}");
        $links = ($links) ? $links : Configure::read("Api.Routes.{$hyperlinkedComponent}");

        $response = [
            'message' => $message,
            'success' => true,
            'url' => Router::url($this->here, true),
            'error' => $error,
            'links' => $links,
            'data' => $data
        ];
    
        $this->response = $this->response->withStatus($code);
        $this->set($response);
    }

    /**
     * Respond to the requestor indicating failure or error
     * 
     * @param string|array $error
     * @param string $message
     * @param string $data
     * @return void
     */
    protected function respondError($error, int $code, string $message = "", array $data = []): void
    {
        Log::write('error', $code . ": " . $message . " (" . Router::url($this->here, true) . ")");
        Log::write('error', $error);

        $componentName = $this->replaceString("Component", "s", $this->plugin);

        $response = [
            'message' => $message,
            'success' => false,
            'url' => Router::url($this->here, true),
            'error' => $error,
            'links' => Configure::read("Api.Routes.{$componentName}"),
            'data' => $data
        ];

        $this->response = $this->response->withStatus($code);
        $this->set($response);
    }

    /**
     * Respond to the requestor indicating a raised exception
     *
     * @param Exception $ex
     * @param string $message
     * @param array $data
     * @return void
     */
    protected function respondException(Exception $ex, string $message = "", int $code = null, array $data = []): void
    {
        Log::write('error', $ex);

        $componentName = $this->replaceString("Component", "s", $this->plugin);

        $response = [
            'message' => $message,
            'success' => false,
            'url' => Router::url($this->here, true),
            'error' => $ex->getMessage(),
            'links' => Configure::read("Api.Routes.{$componentName}"),
            'data' => $data
        ];

        if ($ex->getCode() < 600) {
            $this->response = $this->response->withStatus($ex->getCode());
        }
        else {
            $this->response = $this->response->withStatus($code);
        }

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
    protected function respondFile($location, $filename, $download = true): void
    {
        $this->response->withHeader('Access-Control-Allow-Origin','*');
        $this->response = $this->response->withStatus(201);
        $this->response = $this->response->withFile($location, ['name' => $filename, 'download' => $download]);
    }


    /**
     * Assert the response type is of a given method(s) or raise exception
     *
     * @param string|array $methods
     * @throws MethodNotAllowedException
     * @return void
     */
    protected function requestFailWhenNot($methods): void
    {      
        if (!is_array($methods) && !$this->request->is($methods)) {
            throw new MethodNotAllowedException("HTTP Method disabled for endpoint: Use {$methods}");
        }

        if (is_array($methods) && !in_array($this->request->getMethod(), $methods)) {
            $availableMethods = implode(" OR ", $methods);
            throw new MethodNotAllowedException("HTTP Method disabled for endpoint: Use {$availableMethods}");
        }
    }

    /**
     * Replace a character (or sequence thereof) in a string
     *
     * @param string $target
     * @param string $patch
     * @param string $string
     * @return string
     */
    protected function replaceString($target, $patch, $string): string
    {
        return str_replace($target, $patch, $string);
    }

    private function setCorsHeaders(): void
    {
        // For development purposes only
        $this->response
            ->cors($this->request)
            ->allowOrigin(['*'])
            ->allowOrigin(['http://localhost:8080/'])
            ->allowMethods(['*'])
            ->build();

        $this->response = $this->response->withHeader('Access-Control-Allow-Origin','*');
    }
}
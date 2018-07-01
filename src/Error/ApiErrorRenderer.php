<?php

namespace Prontostoreus\Api\Error;

use Cake\Error\ExceptionRenderer;

class ApiExceptionRenderer extends ExceptionRenderer
{   
    public function render($error)
    {
        $request = $this->controller->request;
        $response = $this->controller->response;

        // return $response->withStringBody('Oops that widget is missing.');
    }
}

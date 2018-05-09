<?php

namespace Prontostoreus\Api\Controller;

use Cake\Event\Event;

trait CycleHydrationTrait
{
    public function beforeRender(Event $event)
    {
        // Ensure we render only JSON responses
        $this->RequestHandler->renderAs($this, 'json');
        $this->set('_serialize', true);
    }
}
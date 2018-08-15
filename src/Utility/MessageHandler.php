<?php

namespace Prontostoreus\Api\Utility;

use Prontostoreus\Api\Utility\FileLoader;
use Cake\Filesystem\Folder;

class MessageHandler
{
    private $loader;
    private $responseMessages = array();

    /**
     * Create an Api Response Message Requestor
     * @param Cake\Filesystem\Folder folderObject
     * @param string file
     */
    public function __construct($folder, $file)
    {
        if (!empty($folder) && !empty($file)) {
            $this->loader = new FileLoader($folder, $file);
        }
        else {
            throw new \InvalidArgumentException("Cannot create ApiResponder: Invalid folder or file.");
        }
    }

    /**
     * Get the message associated with a given key, under a given heading
     * @param string heading The heading under which the desired message resides
     * @param string key The reference key of the message
     * @return string Message associated with the key
     */
    public function retrieve($heading, $key, $args = null): string
    {
        $this->fetchMessages();

        if (!\array_key_exists($heading, $this->responseMessages)) {
            throw new \ErrorException("Response message heading [{$heading}] not found.");
        }
        
        if (!\array_key_exists($key, $this->responseMessages[$heading])) {
            throw new \ErrorException("Response message key [{$key}] not found in [{$heading}].");
        }
        
        return trim(vsprintf($this->responseMessages[$heading][$key], $args));
    }

    private function fetchMessages()
    {   
        $this->responseMessages = json_decode($this->loader->getContents(), true);
    }
}
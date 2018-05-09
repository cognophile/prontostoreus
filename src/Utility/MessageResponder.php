<?php

namespace Prontostoreus\Api\Utility;

use Prontostoreus\Api\Utility\FileLoader;
use Cake\Filesystem\Folder;

class MessageResponder 
{
    private $loader;
    private $responseMessages = array();

    /**
     * Create a MessageResponder
     * @param Cake\Filesystem\Folder folderObject
     * @param string file
     */
    public function __construct($folder, $file)
    {
        if (!empty($folder) && !empty($file)) {
            $this->loader = new FileLoader($folder, $file);
        }
        else {
            throw new \InvalidArgumentException("Cannot create MessageResponder: Invalid folder or file.");
        }
    }

    /**
     * Fetch the message associated with a given key, under a given heading
     * @param string heading The heading under which the desired message resides
     * @param string key The reference key of the message
     * @return string Message associated with the key
     */
    public function getMessage($heading, $key, $args = null)
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
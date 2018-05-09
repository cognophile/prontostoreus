<?php 

namespace Prontostoreus\Api\Utility;

use Cake\Filesystem\Folder;

class FileLoader 
{
    private $folder;
    private $filename;
    private $folderContents;
    private $fileContents;

    /**
     * Create a FileLoader object
     * @param Cake\Filesystem\Folder folderObject
     * @param string filename
     * @return FileLoader 
     */
    public function __construct($folder, $filename)
    {
        if (!empty($folder) && !empty($filename)) {
            $this->folder = $folder;
            $this->filename = $filename;
        }
        else {
            throw new \InvalidArgumentException("Cannot create FileLoader: Invalid folder object or filename.");
        }
    }

    /**
     * Fetch the contents of the file, if available
     * @return string 
     */
    public function getContents()
    {
        $this->readFolder();
        $this->findFile();
        $this->readFile();

        return $this->fileContents;
    }

    private function readFolder()
    {
        $path_parts = pathinfo($this->filename);
        $ext = $path_parts['extension'];

        $this->folderContents = $this->folder->find(".*\{$ext}", true);
    }

    private function findFile()
    {
        foreach ($this->folderContents as $key => $filename) 
        {    
            if ($filename == $this->filename) {
                $this->readFile();
                return; 
            }
        }
    }

    private function readFile() 
    {
        if (file_exists($this->folder->pwd() . $this->filename)) {
            $this->fileContents = file_get_contents($this->folder->pwd() . $this->filename);
        }
        else {
            throw new \ErrorException("File [{$this->filename}] cannot be read. Check it exists in [{$this->folder->pwd()}].");
        }
    }
}
<?php

namespace Prontostoreus\Api\Test\TestCase\Utility;

use Prontostoreus\Api\Utility\FileLoader;
use Cake\TestSuite\TestCase;
use Cake\Filesystem\Folder;

class FileLoaderTest extends TestCase
{
    private $loader;

    public function setUp()
    {
        parent::setUp();

        $folder = new Folder(dirname(dirname(__DIR__)) . "/resources/");
        $this->loader = new FileLoader($folder, "test.json");
    }
    
    public function testGetContentsWithValidFileShouldReturnFullContents()
    {
        $messages = [
            "ExampleHeading" => [
                "ExampleKey" => "ExampleMessage"
            ]
        ];

        $expected = json_encode($messages, JSON_PRETTY_PRINT);

        $actual = $this->loader->getContents();

        $this->assertEquals($expected, $actual);
    }

    public function testFileLoaderInstantiationWithInvalidFolderAgumentsShouldThrowInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $actual = new FileLoader("", "test.json");
    }

    public function testMessageResponderInstantiationWithInvalidFilenameAgumentsShouldThrowInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $actual = new FileLoader(new Folder(dirname(dirname(__DIR__)) . "/resources/"), "");
    }

    public function testGetContentsForNonExistentFileShouldThrowErrorException()
    {
        $this->expectException(\ErrorException::class);

        $fakeLoader = new FileLoader(new Folder(dirname(dirname(__DIR__)) . "/resources/"), "fake.json");
        $actual = $fakeLoader->getContents();
    }
}

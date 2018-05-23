<?php

namespace Prontostoreus\Api\Test\TestCase\Utility;

use Cake\TestSuite\TestCase;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Prontostoreus\Api\Utility\FileLoader;
use Prontostoreus\Api\Utility\MessageHandler;

class MessageHandlerTest extends TestCase
{
    private $messageHandler;

    public function setUp()
    {
        parent::setUp();

        $this->messageHandler = new MessageHandler(
            new Folder(dirname(dirname(__DIR__)) . Configure::read('Folder.TestResources')), 
            Configure::read('File.TestResponseMessages'));
    }
    
    public function testRetrieveWithValidHeadingAndKeysShouldReturnExpectedMessageValue()
    {
        $expected = "ExampleMessage";
        $actual = $this->messageHandler->retrieve("ExampleHeading", "ExampleKey");

        $this->assertEquals($expected, $actual);
    }

    public function testMessageHandlerInstantiationWithInvalidFolderAgumentsShouldThrowInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $actual = new MessageHandler("", Configure::read('File.TestResponseMessages'));
    }

    public function testMessageHandlerInstantiationWithInvalidFilenameAgumentsShouldThrowInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $actual = new MessageHandler(
            new Folder(dirname(dirname(__DIR__)) . Configure::read('Folder.TestResources')), "");
    }

    public function testRetrieveWithInvalidHeadingShouldThrowErrorException()
    {
        $this->expectException(\ErrorException::class);
        $this->messageHandler->retrieve("InvalidHeader", "ExampleKey");
    }

    public function testRetrieveWithInvalidKeyShouldThrowErrorException()
    {
        $this->expectException(\ErrorException::class);
        $this->messageHandler->retrieve("ExampleHeading", "InvalidKey");
    }
}

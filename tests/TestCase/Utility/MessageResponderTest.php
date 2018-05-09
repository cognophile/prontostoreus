<?php

namespace Prontostoreus\Api\Test\TestCase\Utility;

use Prontostoreus\Api\Utility\FileLoader;
use Prontostoreus\Api\Utility\MessageResponder;
use Cake\TestSuite\TestCase;
use Cake\Filesystem\Folder;

class MessageResponderTest extends TestCase
{
    private $responder;

    public function setUp()
    {
        parent::setUp();

        $this->responder = new MessageResponder(
            new Folder(dirname(dirname(__DIR__)) . "/resources/"), "test.json");
    }
    
    public function testGetMessageWithValidHeadingAndKeysShouldReturnExpectedMessageValue()
    {
        $expected = "ExampleMessage";
        $actual = $this->responder->getMessage("ExampleHeading", "ExampleKey");

        $this->assertEquals($expected, $actual);
    }

    public function testMessageResponderInstantiationWithInvalidFolderAgumentsShouldThrowInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $actual = new MessageResponder("", "test.json");
    }

    public function testMessageResponderInstantiationWithInvalidFilenameAgumentsShouldThrowInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $actual = new MessageResponder(new Folder(dirname(dirname(__DIR__)) . "/resources/"), "");
    }

    public function testGetMessageWithInvalidHeadingShouldThrowErrorException()
    {
        $this->expectException(\ErrorException::class);
        $this->responder->getMessage("InvalidHeader", "ExampleKey");
    }

    public function testGetMessageWithInvalidKeyShouldThrowErrorException()
    {
        $this->expectException(\ErrorException::class);
        $this->responder->getMessage("ExampleHeading", "InvalidKey");
    }
}

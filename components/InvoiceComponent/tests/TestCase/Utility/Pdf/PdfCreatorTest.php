<?php

namespace InvoiceComponent\Test\TestCase\Utility\Pdf;

use Cake\TestSuite\TestCase;
use InvoiceComponent\Utility\Pdf\PdfCreator;

class PdfCreatorTest extends TestCase
{
    public $fixtures = [

    ];

    public function testObjectInstantiationWithMissingOutputLocationThrowsArgumentCountError()
    {
        $this->expectException(\ArgumentCountError::class);
        $creator = new PdfCreator();
    }

    public function testObjectInstantiationWithEmptyConfigArraySucceeds()
    {
        $creator = new PdfCreator('/path/to/output/', []);
        $this->assertInstanceOf(PdfCreator::class, $creator);
    }

    public function testSetTemplatesWithMissingParametersThrowsArgumentCountError()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\ArgumentCountError::class);
        $creator->setTemplates();
    }

    public function testSetContentsWithMissingParametersThrowsArgumentCountError()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\ArgumentCountError::class);
        $creator->setContents();
    }

    public function testSetOutputLocationWithMissingParametersThrowsIArgumentCountError()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\ArgumentCountError::class);
        $creator->setOutputLocation();
    }

    public function testRenderWithMissingContentsAndTemplatesPropertiesThrowsArgumentCountError()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\InvalidArgumentException::class);
        $creator->render();
    }

    public function testSetTemplatesWithInvalidParameterValueThrowsIllegalArgumentException()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\InvalidArgumentException::class);
        $creator->setTemplates("", "");
    }

    public function testSetTemplatesWithInvalidNumericParameterTypeThrowsIllegalArgumentException()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\InvalidArgumentException::class);
        $creator->setTemplates(1, 1);
    }

    public function testSetTemplatesWithInvalidArrayParameterTypeThrowsIllegalArgumentException()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\InvalidArgumentException::class);
        $creator->setTemplates(['test'], ['test']);
    }

    public function testSetContentsWithInvalidParameterValueThrowsIllegalArgumentException()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\InvalidArgumentException::class);
        $creator->setContents("");
    }

    public function testSetOutputLocationWithInvalidParameterValueThrowsIllegalArgumentException()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\InvalidArgumentException::class);
        $creator->setOutputLocation("");
    }

    public function testSetOutputLocationWithInvalidNumericParameterTypeThrowsIllegalArgumentException()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\InvalidArgumentException::class);
        $creator->setOutputLocation(1);
    }

    public function testSetOutputLocationWithInvalidArrayParameterTypeThrowsIllegalArgumentException()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\InvalidArgumentException::class);
        $creator->setOutputLocation(['test']);
    }

    public function testConfigureWithInvalidParameterValueThrowsIllegalArgumentException()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\InvalidArgumentException::class);
        $creator->configure([]);
    }

    public function testConfigureWithInvalidStringParameterTypeThrowsIllegalArgumentException()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\TypeError::class);
        $creator->configure("");
    }

    public function testConfigureWithInvalidNumericParameterTypeThrowsIllegalArgumentException()
    {
        $creator = new PdfCreator('/path/to/output/', []);

        $this->expectException(\TypeError::class);
        $creator->configure(1);
    }
}
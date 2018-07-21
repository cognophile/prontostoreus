<?php

namespace InvoiceComponent\Utility\Pdf;

use Cake\Core\Configure;
use CakePdf\Pdf\CakePdf;

class PdfCreator
{
    private $cakePdf;
    private $config;
    private $layoutFile;
    private $templateFile;
    private $outputLocation;
    
    public function __construct($outputLocation, array $additionalConfig = [])
    {
        if (!$outputLocation) {
            throw new \InvalidArgumentException('A valid output location path and filename must be provided');
        }

        if (!empty($configuration)) {
            $this->configure($additionalConfig);
        }

        $this->outputLocation = $outputLocation;
        $this->cakePdf = new CakePdf();
    }

    public function configure(array $additionalConfig): void
    {
        if (!$additionalConfig) {
            throw new \InvalidArgumentException('A valid configuration array must be provided');
        }
        
        $this->config = Configure::read('CakePdf');
        Configure::write('CakePdf', array_unique(array_merge_recursive($this->config, $additionalConfig)));
        $this->config = Configure::read('CakePdf');
    }

    public function setTemplates($templateFile, $layoutFile): void
    {   
        if (!$templateFile || !$layoutFile) {
            throw new \InvalidArgumentException('Valid template and layout files must be provided');
        }

        $this->templateFile = $templateFile;
        $this->layoutFile = $layoutFile;

        $this->cakePdf->template($templateFile, $layoutFile);
    }

    public function setContents($contents): void
    {   
        if (!$contents) {
            throw new \InvalidArgumentException('Valid file content must be provided');
        }

        $this->cakePdf->viewVars($contents);
    }

    public function setOutputLocation($location): void
    {
        if (!$location) {
            throw new \InvalidArgumentException('A valid output location path and filename must be provided');
        }

        $this->outputLocation = $location;
    }

    public function render(): bool
    {
        if (!$this->templateFile || !$this->layoutFile) {
            throw new \InvalidArgumentException('Cannot create PDF: A valid template or layout file was not configured');
        }

        if (!$this->outputLocation) {
            throw new \InvalidArgumentException('Cannot create PDF: A valid output locaiton path and filename was not configured');
        }

        return $this->cakePdf->write($this->outputLocation);
    }
}

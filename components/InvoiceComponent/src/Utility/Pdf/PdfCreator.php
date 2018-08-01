<?php

namespace InvoiceComponent\Utility\Pdf;

use Cake\Core\Configure;
use CakePdf\Pdf\CakePdf;

class PdfCreator
{
    private $cakePdf;
    private $config;
    private $contents;
    private $layoutFile;
    private $templateFile;
    private $outputLocation;
    
    public function __construct($outputLocation, array $additionalConfig = [])
    {
        if (!$outputLocation || !is_string($outputLocation)) {
            throw new \InvalidArgumentException('A valid output location path and filename must be provided');
        }

        if (!empty($additionalConfig)) {
            $this->configure($additionalConfig);
        }

        $this->outputLocation = $outputLocation;
        $this->cakePdf = new CakePdf();
    }

    /**
     * Append additional options to the configuration of this PDF library
     *
     * @param array $additionalConfig
     * @return void
     */
    public function configure(array $additionalConfig): void
    {
        if (!$additionalConfig || !is_array($additionalConfig)) {
            throw new \InvalidArgumentException('A valid configuration array must be provided');
        }
        
        $this->config = Configure::read('CakePdf');
        Configure::write('CakePdf', array_unique(array_merge_recursive($this->config, $additionalConfig)));
        $this->config = Configure::read('CakePdf');
    }

    /**
     * Set the layout and body templates to use, found under 'Template/'
     *
     * @param string $templateFile
     * @param string $layoutFile
     * @return void
     */
    public function setTemplates($templateFile, $layoutFile): void
    {   
        if (!$templateFile || !$layoutFile || !is_string($templateFile) || !is_string($layoutFile)) {
            throw new \InvalidArgumentException('Valid template and layout files must be provided');
        }

        $this->templateFile = $templateFile;
        $this->layoutFile = $layoutFile;

        $this->cakePdf->template($this->templateFile, $this->layoutFile);
    }

    /**
     * Set the data contents for the file
     *
     * @param mixed $contents
     * @return void
     */
    public function setContents($contents): void
    {   
        if (!$contents) {
            throw new \InvalidArgumentException('Valid file content must be provided');
        }

        $this->contents = $contents;

        $this->cakePdf->viewVars($this->contents);
    }

    /**
     * Set the location to output the produced pdf
     *
     * @param [type] $location
     * @return void
     */
    public function setOutputLocation($location): void
    {
        if (!$location || !is_string($location)) {
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

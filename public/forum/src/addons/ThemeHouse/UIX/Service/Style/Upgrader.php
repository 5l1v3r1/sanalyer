<?php

namespace ThemeHouse\UIX\Service\Style;

use XF\Util\File;

class Upgrader extends \XF\Service\AbstractService
{
    protected $product;
    protected $version;
    protected $style;

    public function __construct(\XF\App $app, Array $product, Array $version, \XF\Entity\Style $style)
    {
        parent::__construct($app);

        $this->product = $product;
        $this->style = $style;
        $this->version = $version;
    }

    public function upgrade($tempDir, array $childStyleNames = null)
    {
        if ($childStyleNames === null) {
            $childStyleNames = [];
        }
        $dirHandle = opendir($tempDir);

        $xmlFile = false;
        while ($file = readdir($dirHandle)) {
            if (strpos($file, '.xml') !== false && strpos($file, 'style-') !== false) {
                $xmlFile = $tempDir . DIRECTORY_SEPARATOR . $file;
            }
        }

        if (!$xmlFile) {
            File::deleteDirectory($tempDir);
            return [
                'status' => 'error',
                'error' => 'Unable to find style XML please <a href="https://www.themehouse.com/contact/create-ticket">create a ticket</a> for support.',
            ];
        }

        $importResponse = $this->importStyle($xmlFile, $childStyleNames);
        if ($importResponse['status'] === 'error') {
            File::deleteDirectory($tempDir);
            return $importResponse;
        }

        File::deleteDirectory($tempDir);
        return [
            'status' => 'success',
        ];
    }

    public function importStyle($xmlFile, array $childStyleNames)
    {
        /** @var \XF\Service\Style\Import $styleImporter */
        $styleImporter = $this->service('XF:Style\Import');

        try {
            $document = \XF\Util\Xml::openFile($xmlFile);
        } catch (\Exception $e) {
            $document = null;
        }

        if (!$styleImporter->isValidXml($document, $error))
        {
            return [
                'status' => 'error',
                'error' => $error,
            ];
        }

        $styleImporter->setOverwriteStyle($this->style);
        $styleImporter->importFromXml($document);

        $this->style->th_product_version_uix = $this->version['version'];
        $this->style->th_child_style_cache_uix = $childStyleNames;
        $this->style->save();

        return [
            'status' => 'success',
        ];
    }
}
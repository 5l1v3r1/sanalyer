<?php

namespace ThemeHouse\UIX\Service\Style;

use \XF\Util\File;

class Mover extends \XF\Service\AbstractService
{
    protected $source;
    protected $destination;
    protected $ftpConnection;

    public function __construct(\XF\App $app, $source, $destination, \ThemeHouse\UIX\Util\Ftp $ftpConnection = null)
    {
        parent::__construct($app);

        $newSource = $this->validateSource($source);

        if (!$newSource) {
            throw new \Exception('Error locating product files in ' . $source);
        }

        $this->source = $newSource;
        $this->destination = $destination;
        $this->ftpConnection = $ftpConnection;
    }

    public function move()
    {
        return $this->rmove($this->source, $this->destination);
    }

    protected function validateSource($source)
    {
        $validDirectories = [
            'upload', 'uploads', 'Upload', 'Uploads',
        ];

        $directories = array_filter(glob($source . DIRECTORY_SEPARATOR . '*'), 'is_dir');

        foreach ($directories as $directory) {
            $parts = explode(DIRECTORY_SEPARATOR, $directory);
            $subDir = end($parts);

            if (in_array($subDir, $validDirectories)) {
                return $directory . DIRECTORY_SEPARATOR;
            }
        }

        return false;
    }

    protected function rmove($dirSource, $dirDest)
    {
        if (is_dir($dirSource)) {
            $dirHandle = opendir($dirSource);
        }

        $dirName = substr($dirSource, strrpos($dirSource, DIRECTORY_SEPARATOR) + 1);
        if (!is_dir($dirDest . DIRECTORY_SEPARATOR . $dirName) && !file_exists($dirDest . DIRECTORY_SEPARATOR . $dirName)) {
            $this->mkdir($dirDest.DIRECTORY_SEPARATOR.$dirName);
        }

        $break = false;
        while ($file = readdir($dirHandle)) {
            if ($file == 'Thumbs.db' || $file == '.DS_Store') {
                $this->rmfile($file);
                continue;
            }

            if ($file != '.' && $file != '..') {
                if (!is_dir($dirSource.DIRECTORY_SEPARATOR.$file)) {
                    if (file_exists($dirDest.DIRECTORY_SEPARATOR.$dirName.DIRECTORY_SEPARATOR.$file)) {
                        $this->rmfile($dirDest.DIRECTORY_SEPARATOR.$dirName.DIRECTORY_SEPARATOR.$file);
                    }
                    $hasCopied = $this->copy($dirSource.DIRECTORY_SEPARATOR.$file, $dirDest.DIRECTORY_SEPARATOR.$dirName.DIRECTORY_SEPARATOR.$file);
                    if (!$hasCopied) {
                        $break = true;
                        break;
                    }
                    $this->rmfile($dirSource.DIRECTORY_SEPARATOR.$file);
                } else {
                    $this->rmove($dirSource.DIRECTORY_SEPARATOR.$file, $dirDest.DIRECTORY_SEPARATOR.$dirName);
                }
            }
        }

        @closedir($dirHandle);
        @rmdir($dirSource);

        if ($break) {
            return [
                'status' => 'error',
                'error' => 'Error copying style files, please check your file permissions',
            ];
        }

        return [
            'status' => 'success',
        ];
    }

    protected function copy($source, $destination)
    {
        $source = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $source);
        $destination = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $destination);
        $source = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $source);
        $destination = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $destination);

        try {
            if ($this->ftpConnection) {
                $this->ftpConnection->move($source, $destination);
            } else {
                copy($source, $destination);
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    protected function mkdir($dirName)
    {
        if ($this->ftpConnection) {
            $this->ftpConnection->mkdir($dirName);
        } else {
            File::createDirectory($dirName);
        }
    }

    protected function rmfile($fileName)
    {
//        @unlink($fileName);
    }
}
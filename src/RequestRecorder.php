<?php

namespace Edu\IU\VPCM\RequestRecorder;

class RequestRecorder
{
    public $fileName;
    public $folderPath;
    public $logName = 'error.log';
    public $extra;

    public function __construct($folderPath, $fileName, $extra = [])
    {
        $this->fileName = $fileName;
        $this->folderPath = $folderPath;
        $this->extra = $extra;
    }


    public function append()
    {
        $entry = $this->formatEntry();
        $this->createFolder();
        $this->createPath();
        try {
            file_put_contents($this->filePath, $entry, FILE_APPEND);
        } catch (\RuntimeException $e) {
            $filePath = $this->folderPath . DIRECTORY_SEPARATOR . $this->logName;
            file_put_contents($filePath, $e->getMessage() . PHP_EOL, FILE_APPEND);
        }
    }

    public function buildEntry(): array
    {
        $now = $this->getNow();
        $ip = $this->getIP();
        $uri = $this->getRequestURI();
        $method = $this->getRequestMethod();
        $extra = $this->extra;
        return compact('now', 'ip', 'uri', 'method', 'extra');
    }


    public function formatEntry(): string
    {
        $entry = $this->buildEntry();

        return json_encode($entry);
    }


    public function getIP()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'xxx.xxx.xxx.xxx';
    }

    public function getNow()
    {
        $now = new \DateTime();

        return $now->format('Y-m-d H:i:s');
    }

    public function getRequestURI()
    {
        return isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
    }

    public function getRequestMethod()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null;
    }

    public function createFolder()
    {
        if (!file_exists($this->folderPath)) {
            mkdir($this->folderPath);
        }

    }

    public function createPath()
    {
        $filePath = $this->folderPath . DIRECTORY_SEPARATOR . $this->fileName;
        if (!file_exists($filePath)) {
            touch($filePath);
        }
    }
}
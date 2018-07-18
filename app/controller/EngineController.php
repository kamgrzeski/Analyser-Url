<?php

ini_set('max_execution_time', 0); //max script run

require_once($_SERVER['DOCUMENT_ROOT'] . '/Analyser-Url/app/service/FileService.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Analyser-Url/app/service/RegExpService.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Analyser-Url/app/service/DomainService.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Analyser-Url/app/service/HelpersService.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Analyser-Url/app/service/MessagesService.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Analyser-Url/app/service/CurlService.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Analyser-Url/app/service/DOMService.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Analyser-Url/app/service/LangService.php');

class EngineController
{
    private $fileService;

    private $regExpService;

    private $domainService;

    private $helpersService;

    private $messagesService;

    private $curlService;

    private $domService;

    private $langService;

    public function __construct()
    {
        $this->fileService = new FileService();
        $this->regExpService = new RegExpService();
        $this->domainService = new DomainService();
        $this->helpersService = new HelpersService();
        $this->messagesService = new MessagesService();
        $this->curlService = new CurlService();
        $this->domService = new DOMService();
        $this->langService = new LangService();
    }

    public function getFileService()
    {
        return $this->fileService;
    }

    public function getRegExpService()
    {
        return $this->regExpService;
    }

    public function getDomainService()
    {
        return $this->domainService;
    }

    public function getHelpersService()
    {
        return $this->helpersService;
    }

    public function getMessagesService()
    {
        return $this->messagesService;
    }

    public function getCurlService()
    {
        return $this->curlService;
    }

    public function getDOMService()
    {
        return $this->domService;
    }

    public function getLandService()
    {
        return $this->langService;
    }
}
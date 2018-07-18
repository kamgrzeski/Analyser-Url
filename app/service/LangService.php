<?php

class LangService
{
    public $lang;

    public function __construct()
    {
        $this->lang = require_once($_SERVER['DOCUMENT_ROOT'] . '/Analyser-Url/app/lang/eng.php');
    }

    public function getLang()
    {
        return $this->lang;
    }
}
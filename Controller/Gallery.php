<?php

namespace SoosyzeExtension\Gallery\Controller;

class Gallery extends \Soosyze\Controller
{
    public function __construct()
    {
        $this->pathServices = dirname(__DIR__) . '/Config/service.json';
    }
}

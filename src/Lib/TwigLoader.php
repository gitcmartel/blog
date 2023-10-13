<?php

namespace Application\Lib;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Application\lib\Path;

abstract class TwigLoader
{
    private static ?Environment $environment = null;

    private function construct() : Environment 
    {
        $loader = new FilesystemLoader(Path::getRootPath() . '\templates');
        return new Environment($loader, ['cache' => false]);
    }

    public static function getEnvironment() : Environment
    {
        if(static::$environment === null){
            static::$environment = static::construct();
        }
        return static::$environment;
    }
}
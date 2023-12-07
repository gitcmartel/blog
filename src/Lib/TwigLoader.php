<?php

namespace Application\Lib;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

abstract class TwigLoader
{
    #region Properties

    private static ?Environment $environment = null;

    #endregion

    #region Functions
    private static function construct() : Environment 
    {
        $loader = new FilesystemLoader(Path::getRootPath() . '/templates');
        return new Environment($loader, ['cache' => false]);
    }

    public static function getEnvironment() : Environment
    {
        if(static::$environment === null){
            static::$environment = static::construct();
        }
        return static::$environment;
    }

    #endregion
}
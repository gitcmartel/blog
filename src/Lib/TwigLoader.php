<?php

namespace Application\Lib;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Application\lib\Path;

class TwigLoader
{
    public static function getEnvironment() : Environment
    {
        $loader = new FilesystemLoader(Path::getRootPath() . '\templates');
        return new Environment($loader, ['cache' => false]);
    }
}
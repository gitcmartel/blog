<?php

namespace Application\Lib;

class Path 
{
    /**
     * Build a path with the OS corresponding separator
     * So if the application is hosted on Windows or linux it could work on both systems
     */
    public static function fileBuildPath(array $arguments) : string
    {
        if (!empty($arguments))
            return join(DIRECTORY_SEPARATOR, $arguments);
        else {
            return "";
        }
    }
}
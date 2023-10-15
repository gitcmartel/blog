<?php

namespace Application\Lib;

class Path 
{
    #region Functions

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

    /**
     * Returns the absolute path of the project
     */
    public static function getRootPath() : string
    {
        return dirname(__FILE__, 3);
    }

    #endregion
}
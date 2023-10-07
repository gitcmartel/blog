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

    /**
     * Returns the absolute path of the project
     */
    public static function getRootPath() : string
    {
        return dirname(__FILE__, 3);
    }

    //Returns an array that contains the namspace's list
    public function getClasses(string $action) : array
    {
        return self::getDirectories(self::getRootPath() . '\\src', "", $action);
    }

    /**
     * Scans the src directory architecture to retrieve all folders
     */
    private function getDirectories($directory, $sbdirectory, string $action) : array
    {
        $classes = array();
        $projectRootClass = "Application";
        if (is_dir($directory)){
            $subdirectories = scandir($directory);
            foreach($subdirectories as $subdirectory){
                if ($subdirectory != '.' && $subdirectory != '..') {
                    $subdirectoryPath = $directory . "\\" . $subdirectory;
                    if(is_dir($subdirectoryPath)){
                        $classes[] = $projectRootClass . $sbdirectory . '\\' . $subdirectory . '\\' . $action;
                        $classes = array_merge($classes, self::getDirectories($subdirectoryPath, $sbdirectory . '\\' . $subdirectory, $action));
                    }
                }
            }
        }
        return $classes;
    }
}
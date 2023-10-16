<?php

namespace Application\Lib;

class Xml
{
    #region Functions

    //Convert an xml file into an array object
    public static function convertToObject(string $path) : object
    {
        if(! is_readable($path)){
            return null;
        }

        //Read entire file into string
        $xmlFile = file_get_contents($path);

        //Convert xml string into an object
        $xmlObject = simplexml_load_string($xmlFile);

        return $xmlObject;
    }

    #endregion
}

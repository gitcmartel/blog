<?php

namespace Application\Lib;

class Xml
{
    //Convert an xml file into an array object
    public static function convertToArray(string $path) : object
    {
        //Read entire file into string
        $xmlFile = file_get_contents($path);

        //Convert xml string into an object
        $xmlObject = simplexml_load_string($xmlFile);

        //Convert object into json
        $json = json_encode($xmlObject);

        //Convert json into associative array
        $array = json_decode($json);

        return $array;
    }
}

<?php

namespace Application\Lib;

use XMLReader;
use DOMDocument;

class Xml
{
    #region Functions

    /**
     * Convert an xml file into an array object
     * @param string $path
     * @return object
     */
    public static function convertToObject(string $path): ?object
    {
        if (file_exists($path) == false) {
            return null;
        }
    
        $reader = new XMLReader();

        try {
            if ($reader->open($path) === false) {
                return null;
            }

            // Load xml content
            $doc = new DOMDocument;

            // Use XMLReader to read the file
            while ($reader->read()) {
                if ($reader->nodeType == XMLReader::ELEMENT) {
                    $doc->loadXML($reader->readOuterXML());
                    break;
                }
            }

            // Converting into SimpleXMLElement
            $xmlObject = simplexml_import_dom($doc);

            return $xmlObject;
        } catch (\Exception $exception) {
            return null;
        } finally {
            $reader->close();
        }
    }

    #endregion
}

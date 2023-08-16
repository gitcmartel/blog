<?php

namespace Application\Lib;


class Upload 
{
    /**
     * Checks if the file extension is one of thoses who are allowed
     */
    public static function checkFileType(string $fileName) : bool
    {
        $fileExtension = strtolower(end(explode(".", $fileName)));

        if (in_array(Constants::IMAGE_EXTENSIONS, $fileExtension) === false){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks if the file size does not exceeds the limit allowed
     */
    public static function checkSize(int $fileSize) : bool 
    {
        if ($fileSize > Constants::IMAGE_MAX_SIZE) {
            return false;
        } else {
            return true;
        }
    }

}
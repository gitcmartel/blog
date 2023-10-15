<?php

namespace Application\Lib;


class Upload 
{
    #region Functions

    /**
     * Checks if the file extension is one of thoses who are allowed
     */
    public static function checkFileType(string $fileName) : bool
    {
        $fileExtension = self::getExtension($fileName);

        if (in_array($fileExtension, Constants::IMAGE_EXTENSIONS) === false){
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
        if ($fileSize > Constants::IMAGE_MAX_SIZE)  {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the extension of a file
     */
    public static function getExtension(string $fileName) : string 
    {
    $explode = explode(".", $fileName);
    return strtolower(end($explode));
    }

    #endregion
}
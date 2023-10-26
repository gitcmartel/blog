<?php

namespace Application\Lib;

class Image 
{
    /**
     * Move the image file from the temp server folder, to the the image post folder
     */
    public static function moveTempImageIntoImagePostFolder(string $tmpImagePath, string $destinationPath) : bool
    {
        if (! move_uploaded_file($tmpImagePath, dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $destinationPath)) {
            return false;  
        }
        return true;
    }

    public static function deleteImagePost(string $pathImage)
    {
        //If the image post is not the default image we delete it
        $rootPath = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR;

        if ($pathImage !== Path::fileBuildPath(array("img", Constants::DEFAULT_IMAGE_POST))){
            if (file_exists($rootPath . $pathImage)){
                return unlink($rootPath . $pathImage);
            }
        }
    }
}
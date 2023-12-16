<?php

namespace Application\Lib;

use DateTime;

class Image
{
    #region Functions 

    /**
     * Move the image file from the temp server folder, to the the image post folder
     */
    public static function moveTempImageIntoImagePostFolder(string $tmpImagePath, string $destinationPath): bool
    {
        if (!move_uploaded_file($tmpImagePath, dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $destinationPath)) {
            return false;
        }
        return true;
    }

    public static function deleteImage(string $imagePath)
    {
        //If the image post is not the default image we delete it
        $rootPath = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'public\\';

        if ($imagePath !== Path::fileBuildPath(array("img", Constants::DEFAULT_IMAGE_POST)) && $imagePath !== '') {
            if (file_exists($rootPath . $imagePath)) {
                return unlink($rootPath . $imagePath);
            }
        }
    }

    public static function createImagePathName(int $postId, string $imageName, DateTime $date): string
    {
        return $postId . $date->format("YmdHis") . '.' . File::getExtension($imageName);
    }

    #endregion
}
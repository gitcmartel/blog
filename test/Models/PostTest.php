<?php

use PHPUnit\Framework\TestCase;
use Application\Models\Post;
use Application\Lib\Constants;
use Application\Lib\Path;

class PostTest extends TestCase
{
    /**
     * 
     */
    public function testDeleteImageTrue()
    {
        //Copy the default image post in the img-post directory
        $rootPath = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR;
        $defaultPostImagePath = Path::fileBuildPath(array('img', Constants::DEFAULT_IMAGE_POST));
        $testFilePath = Path::fileBuildPath(array('img', 'img-post', Constants::DEFAULT_IMAGE_POST));

        copy($rootPath . $defaultPostImagePath, $rootPath . $testFilePath);

        //Preparing test environnement
        $post = new Post();
        $post->imagePath = $testFilePath;

        //Getting the function result
        $result = $post->deleteImage();

        //If the function fails we have to delete the image test manualy
        if (! $result) {
            unlink($testFilePath);
        }

        //Executing test
        $this->assertTrue($result);
    }
}
<?php

use PHPUnit\Framework\TestCase;
use Application\Models\Post;
use Application\Lib\Constants;
use Application\Lib\Path;

class PostTest extends TestCase
{
    /**
     * Test a true result 
     */
    public function testDeleteImageTrue()
    {
        //Copy the default image post in the img-post directory
        $rootPath = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR;
        $defaultPostImagePath = Path::fileBuildPath(array('img', Constants::DEFAULT_IMAGE_POST));
        $testFilePath = Path::fileBuildPath(array('img', 'img-posts', Constants::DEFAULT_IMAGE_POST));

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

    /**
     * Test a false result with a file that does not exist
     */
    public function testDeleteImageFalse()
    {
        //Preparing test environnement
        $rootPath = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR;
        $fakeImagePath = Path::fileBuildPath(array('img', 'img-posts', 'fake-image.jpg'));

        $post = new Post();
        $post->imagePath = $rootPath . $fakeImagePath;

        //Getting the function result
        $result = $post->deleteImage();

        //Executing test
        $this->assertFalse($result);
    }
}
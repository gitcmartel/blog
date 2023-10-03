<?php

use PHPUnit\Framework\TestCase;
use Application\Lib\Path;

class PathTest extends TestCase
{
    /**
     * Test that the function returns a string
     */
    public function testFileBuildPathTrue()
    {
        $array = array("img", "blog-post.svg"); //Valid array
        
        $this->assertTrue(Path::fileBuildPath($array) !== "");
    }

    /**
     * Test that the function returns a string
     */
    public function testFileBuildPathFalse()
    {
        $array = array(); //Empty array
    
        $this->assertFalse(Path::fileBuildPath($array) !== "");
    }
}
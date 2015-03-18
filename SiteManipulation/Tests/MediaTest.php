<?php
include_once 'Media.php';
use SiteManipulation\Media;
class MediaTest extends PHPUnit_Framework_TestCase
{

    protected $object;

    protected function setUp()
    {
        $this->object = new Media();
    }


    public function testdownloadFileURL()
    {
        $this->assertInternalType("int", $this->object->downloadFileURL('http://www.example.com/test.pdf','/path/download/'));
    }

}
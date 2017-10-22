<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 12.08.2017
 * Time: 22:11
 */
require "../modules/image_search/image_search.php";
class image_search_test extends PHPUnit_Framework_TestCase
{

  /*  public function testImageDataGetImageDataNoImage(){
        $result = ItemData::getImageData(0);
        $this->assertNull($result);
    }*/
    public function testImageDataGetImageData(){
        $result = ItemData::getImageData(1);
        print_r($result->file_name);
        $this->assertTrue(get_class($result) == "ImageData");
    }
/*
    public function testSearchSimilars(){
        $sex = "Erkek";
        $category = "Erkek Mont";
        $fileurl = "/home/murat/Projects/TrendBul/Database/Erkek/Erkek Mont/0.jpg";
        $result = searchSimilars($sex,$category,$fileurl,COLOR);
        $before = 0;
        foreach ($result as $obj){
            $this->assertTrue($before<=$obj["distance"]);
            $before = $obj["distance"];
        }
    }


    public function testGetSex(){
        $result = getcategories("Kadın");
        print_r($result);
        //$this->assertTrue(get_class($result) == "ImageData");
    }*/
}
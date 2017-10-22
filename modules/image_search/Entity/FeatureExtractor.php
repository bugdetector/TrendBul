<?php
/**
 * User: murat
 * Date: 12.08.2017
 */

abstract class FeatureExtractor
{
    protected $histogram_size;
    abstract public function calculateHistogram(&$imageString);
    abstract function calculateDistance(ImageData &$imageData1,ImageData &$imageData2);

    protected function distance(&$array1, &$array2){
        $distance = 0;
        for($i=0;$i<$this->histogram_size;$i++){
            $distance += pow($array1[$i]-$array2[$i],2);
        }
        return $distance;
    }

    protected function getNormalizedImage(&$imageString){
        $image = imagecreatefromstring($imageString);
        $width = imagesx($image);
        $height = imagesy($image);
        $outwidth=293;
        $outheight=409;
        $StartWidth=0;
        $StartHeight=0;
        if(($width/$height)<($outwidth/$outheight)){
            $StartWidth = ($height%$outheight)/2;
        }else if(($width/$height)<($outwidth/$outheight)){
            $StartHeight = ($height%$outheight)/2;
        }
        $out = imagecreatetruecolor($outwidth, $outheight);
        imagecopyresized($out,$image,0,0,$StartWidth,$StartHeight,$outwidth,$outheight,$width-$StartWidth,$height-$StartHeight);
        $out2 = imagecreatetruecolor($outwidth/3, $outheight/3);
        imagecopyresized($out2, $out, 0, 0, $outwidth/3,$outheight/3, $outwidth/3, $outheight/3, $outwidth/3, $outheight/3);
        return $out2;
    }
}
<?php
/**
 * User: murat
 * Date: 12.08.2017
 */

class ColorFeatureExtractor extends FeatureExtractor
{
    public function __construct()
    {
        $this->histogram_size = 64;
    }

    public function calculateHistogram(&$imageString)
    {
        $histogram = array_fill(0,64,0);
        $image = $this->getNormalizedImage($imageString);
        $width = imagesx($image);
        $heigth = imagesy($image);

        for($i=0;$i<$heigth;$i++){
            for ($j=0;$j<$width;$j++){
                $rgb = imagecolorat($image, $j, $i);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $val = 0;
                $val += $r>>6;
                $val = $val<<2;
                $val += $g>>6;
                $val = $val<<2;
                $val += $b>>6;
                $histogram[$val]++;

            }
        }
        return new ImageData($histogram);
    }
    public function calculateDistance(ImageData &$imageData1,ImageData &$imageData2){
        return $this->distance($imageData1->color_histogram,$imageData2->color_histogram);
    }
}
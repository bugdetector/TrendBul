<?php
/**
 * User: murat
 * Date: 12.08.2017
 */

class PatternFeatureExtractor extends FeatureExtractor
{
    public function __construct()
    {
        $this->histogram_size = 9;
    }

    public function calculateHistogram(&$imageString)
    {
        $histogram = array_fill(0,9,0);
        $image = $this->getNormalizedImage($imageString);
        $width = imagesx($image);
        $heigth = imagesy($image);
        list($mag,$angle) = $this->Sobel($image);
        for($i=0;$i<$heigth;$i++){
            for($j=0;$j<$width;$j++){
                $angleval = imagecolorat($angle, $j, $i)>>16;
                $histogram[$angleval/20]+=imagecolorat($mag, $j, $i)>>16;
            }
        }
        return new ImageData(null,null,$histogram);
    }
    private function Sobel(&$image){//0 -> gx, 1-> gy
        $width = imagesx($image);
        $heigth = imagesy($image);
        $gxfilter = [[1,0,-1],[2,0,-2],[1,0,-1]];
        $gyfilter = [[1,2,1],[0,0,0],[-1,-2,-1]];
        $mag = imagecreatetruecolor($width, $heigth);
        $angle = imagecreatetruecolor($width, $heigth);
        for($i=1;$i<$heigth-1;$i++){
            for($j=1;$j<$width-1;$j++){
                $totalx = 0;
                $totaly = 0;
                for($k=-1;$k<2;$k++){
                    for($l=-1;$l<2;$l++){
                        $rgb = imagecolorat($image,$j+$k,$i+$l);
                        $r = ($rgb >> 16) & 0xFF;
                        $g = ($rgb >> 8) & 0xFF;
                        $b = $rgb & 0xFF;
                        $pixel = round($r * 0.3 + $g * 0.59 + $b * 0.11);
                        $totalx += $pixel*$gxfilter[$k+1][$l+1];
                        $totaly += $pixel*$gyfilter[$k+1][$l+1];
                    }
                }
                $val = sqrt(pow($totalx,2)+pow($totaly,2));
                $angleval = (int)rad2deg((@atan($totalx/$totaly)));
                if($angleval<0) $angleval += 180;
                if($val>255){ $val=255; }
                $color = imagecolorallocate($mag, $val, $val, $val);
                $anglecolor = imagecolorallocate($angle, $angleval, $angleval, $angleval);
                imagesetpixel($mag, $j, $i, $color);
                imagesetpixel($angle, $j, $i, $anglecolor);
            }
        }
        return [$mag,$angle];
    }
    public function calculateDistance(ImageData &$imageData1,ImageData &$imageData2){
        return $this->distance($imageData1->hog,$imageData2->hog);
    }
}
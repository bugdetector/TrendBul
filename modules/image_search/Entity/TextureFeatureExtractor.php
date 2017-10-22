<?php
/**
 * User: murat
 * Date: 12.08.2017
 */

class TextureFeatureExtractor extends FeatureExtractor
{
    public function __construct()
    {
        $this->histogram_size = 256;
    }

    public function calculateHistogram(&$imageString)
    {
        $histogram = array_fill(0,$this->histogram_size,0);
        $image = $this->getNormalizedImage($imageString);
        imagefilter($image,IMG_FILTER_GRAYSCALE);
        $width = imagesx($image);
        $heigth = imagesy($image);

        for ($i = 1;$i<$heigth-1;$i++){
            for ($j = 1;$j<$width-1;$j++){
                $val = 0;
                $change = 0;
                $last = 2;
                $pixel = imagecolorat($image,$j,$i)>>16;
                $val += ((imagecolorat($image,$j-1,$i-1)>>16)>$pixel ? 1:0)<<0;
                $val += ((imagecolorat($image,$j-1,$i  )>>16)>$pixel ? 1:0)<<1;
                $val += ((imagecolorat($image,$j-1,$i+1)>>16)>$pixel ? 1:0)<<2;
                $val += ((imagecolorat($image,$j  ,$i+1)>>16)>$pixel ? 1:0)<<3;
                $val += ((imagecolorat($image,$j+1,$i+1)>>16)>$pixel ? 1:0)<<4;
                $val += ((imagecolorat($image,$j+1,$i  )>>16)>$pixel ? 1:0)<<5;
                $val += ((imagecolorat($image,$j+1,$i-1)>>16)>$pixel ? 1:0)<<6;
                $val += ((imagecolorat($image,$j  ,$i-1)>>16)>$pixel ? 1:0)<<7;
                for($k=0;$k<8;$k++){
                    if(($val>>$k)%2 == 0){
                        if($last==1){
                            $change++;
                        }
                        $last=0;
                    }else{
                        if($last==0){
                            $change++;
                        }
                        $last=1;
                    }
                }
                if($change>2) {
                    $val = 172;
                }
                $histogram[$val]++;
            }
        }
        return new ImageData(null,$histogram);
    }
    public function calculateDistance(ImageData &$imageData1,ImageData &$imageData2){
        return $this->distance($imageData1->lbp_histogram,$imageData2->lbp_histogram);
    }
}
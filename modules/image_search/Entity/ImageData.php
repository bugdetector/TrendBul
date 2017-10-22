<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 12.08.2017
 */

class ImageData
{
    public $color_histogram;
    public $lbp_histogram;
    public $hog;
    public function __construct($color=null,$lbp=null,$hog=null)
    {
        if ($color){
            $this->color_histogram = $color;
        }
        if ($lbp){
            $this->lbp_histogram = $lbp;
        }
        if ($hog){
            $this->hog = $hog;
        }
    }

    public function setColorHistogram($histogramArray){
        $this->color_histogram = explode(",",$histogramArray);
    }
    public function setLBPHistogram($histogramArray){
        $this->lbp_histogram = explode(",",$histogramArray);
    }
    public function setHog($histogramArray){
        $this->hog = explode(",",$histogramArray);
    }
}
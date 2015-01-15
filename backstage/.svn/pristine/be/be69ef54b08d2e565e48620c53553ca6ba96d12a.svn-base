<?php

class hmroll {

    public $image;

    function __construct() {
        
    }

    function create($bg, $thanks, $pics, $iid) { // 产生图片
        $bgimage = ImageCreateFromPNG($bg);
        $bgw = ImageSX($bgimage);
        $bgh = ImageSY($bgimage);
        $this->image = imagecreatetruecolor($bgw, $bgh);

        ImageAlphaBlending($this->image, true);

        $trans_colour = imagecolorallocatealpha($this->image, 0, 0, 0, 127);
        imagefill($this->image, 0, 0, $trans_colour);
        ImageCopy($this->image, $bgimage, 0, 0, 0, 0, $bgw, $bgh);
        imagesavealpha($this->image, true);
        $images = $this->loadImages($thanks, $pics);
        $x = $i = 0;
        foreach ($images as $img) {
            $w = ImageSX($img);
            $h = ImageSY($img);
            $this->putImage($this->image, $img, count($images), $i, $w, $h, $bgw, $bgh);
            imagedestroy($img);
            // ImageCopy($image, $img, $x, 0, 0, 0, $w, $h);
            $i++;
            $x += $w;
        }
        for($j = 0; $j < count($images);$j++) {
            if (is_resource($images[$j])) {
            imagedestroy($images[$j]);
        }
        }
        unset($images);
        $this->imagePng($this->image, $iid);
        // return $this->image;
    }

    //生成png图片
    private function imagePng($image, $iid) {
        imagepng($image, 'views/default/img/roll/' . $iid . '.png');
    }

    private function createImageFromUrl($url) {
        $data = file_get_contents($url);
        return imagecreatefromstring($data);
    }

    private function putImage(&$image, $img, $count, $i, $w, $h, $bgw, $bgh) {
        $range = -1 * $i * 360 / $count;
        $transColor = imagecolorallocatealpha($img, 255, 255, 255, 127);
        $newimg = imagerotate($img, $range - 90, $transColor, 1);
        $logoW = ImageSX($newimg);
        $logoH = ImageSY($newimg);
        $centerx = intval($bgw / 2);
        $centery = intval($bgh / 2);
        $x = $centerx + 140 * cos(deg2rad($range)) - intval($logoW / 2);
        $y = $centery - 140 * sin(deg2rad($range)) - intval($logoH / 2);
        //echo $i . '_' . $range . '_' . $x . '_' . $y . '<br/>';
        ImageCopy($image, $newimg, $x, $y, 0, 0, $logoW, $logoH);
        imagedestroy($newimg);
    }

    private function loadImages($thanks, $pics) {
        $images = array();
        foreach ($pics as $p) {
            $images[] = $this->createNewPic($p);
            $images[] = ImageCreateFromPNG($thanks);
        }
        return $images;
    }

    private function createNewPic($p) {
        $src = ImageCreateFromPNG($p);
        $image = imagecreatetruecolor(64, 64);
        $bgw = ImageSX($src);
        $bgh = ImageSY($src);
        if ($bgw > $bgh) {
            $newbgw = 64;
            $newbgh = ($bgh / $bgw) * 64;
        } else {
            $newbgh = 64;
            $newbgw = ($bgw / $bgh) * 64;
        }
        
        imagesavealpha($image, true);
        $trans_colour = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $trans_colour);
        ImageCopyResized($image, $src, (64-$newbgw)/2, (64-$newbgh)/2, 0, 0, $newbgw, $newbgh, $bgw, $bgh);
        return $image;
    }

    function __destruct() {
        if (is_resource($this->image)) {
            imagedestroy($this->image);
        }
    }

}

?>
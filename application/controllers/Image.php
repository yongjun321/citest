<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image extends CI_Controller{

    public function __construct(){

        parent::__construct();
        date_default_timezone_set('Asia/Shanghai');
        error_reporting(E_ALL&~E_NOTICE&~E_WARNING);
        $this->load->library('curl');
    }

    public function index(){
        //头像
        $headImgUrl = '';
        //背景图片
        $bgUrl      = '';
        $imgs['dst']=$bgUrl;
        //第一步压缩图片
        $imgGzip    = $this->reSizeImg($headImgUrl);
        //第二步生成圆角
        $imgs['src']= $this->createFillet($headImgUrl);
        //第三步合并图片
        $desr       = $this->mergeImg($imgs);
    }

    /**
     * 压缩图片
     * @param string $url 图片地址
     * @param string $path 存放的目录
     * @return string
     */
    public function reSizeImg($url,$path = './'){

        $imgName = $path.uniqid().'.jpg';
        $file    = $url;
        list($width,$height) = getimagesize($file); //获取原图片尺寸
        $percent = (100/$width);
        //缩放尺寸
        $newWidth  = $width * $percent;
        $newHeight = $height * $percent;
        $src_im    = imagecreatefromjpeg($file);
        $dts_im    = imagecreatetruecolor($newWidth,$newHeight);
        imagecopyresized($dts_im,$src_im,0,0,0,0,$newWidth,$newHeight,$width,$height);
        imagejpeg($dts_im,$imgName);
        imagedestroy($dts_im);
        imagedestroy($src_im);
        return $imgName;
    }

    /**
     *  生成圆角
     * @param string $url 图片地址
     * @param string $path 要存放的目录
     * @return string
     */
    public function createFillet($url,$path = './'){

        $w = 100; $h = 100;  //设置图片长宽
        $original_path = $url;
        $destPath      = $path.uniqid().'.png';    //生成图片路径
        $src    = imagecreatefromstring(file_get_contents($original_path));
        $newPic = imagecreatetruecolor($w,$h);
        imagealphablending($bewPic,false);
        $transparent = imagecolorallocatealpha($newPic,0,0,0,127);
        $r = $w / 2;
        for($x=0;$x<$w;$x++){
            for($y=0;$y<$h;$y++) {
                $c = imagecolorat($src, $x, $y);
                $_x = $x - $w / 2;
                $_y = $y - $h / 2;
                if ((($x * $_x) + ($y * $_y)) < ($r * $r)) {
                    imagesetpixel($newPic, $x, $y, $c);
                } else {
                    imagesetpixel($newPic, $_x, $_y, $transparent);
                }
            }
        }
        imagesavealpha($newPic,true);
        imagepng($newPic,$destPath);
        imagedestroy($newPic);
        imagedestroy($src);
        unlink($url);
        return $destPath;
    }

    /**
     * @param array $imgs 需要合并的图片，
     * @param string $path 存放路径目录
     * @return string
     */
    public function mergeImg($imgs,$path = '.'){

        $imgName = $path.rand(1000,9999).uniqid().'.jpg';  //图片名称
        list($width,$height) = getimagesize($imgs['dst']);   //获取图片宽高
        $dests   =  imagecreatetruecolor($width,$height);
        $dstImg  = imagecreatefrompng($imgs['dst']);
        imagecopy($dests,$dstImg,0,0,0,0,$width,$height);
        imagedestroy($dstImg);
        $srcImg  = imagecreatefrompng($imgs['src']);
        $srcInfo = getimagesize($imgs['src']);
        imagecopy($dests,$srcImg,270,202,0,0,$srcInfo[0],$srcInfo[1]);
        imagedestroy($dests,$imgName);
        unlink($imgs['src']);
        return $imgName;
    }
}
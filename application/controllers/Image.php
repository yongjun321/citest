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
        //ͷ��
        $headImgUrl = '';
        //����ͼƬ
        $bgUrl      = '';
        $imgs['dst']=$bgUrl;
        //��һ��ѹ��ͼƬ
        $imgGzip    = $this->reSizeImg($headImgUrl);
        //�ڶ�������Բ��
        $imgs['src']= $this->createFillet($headImgUrl);
        //�������ϲ�ͼƬ
        $desr       = $this->mergeImg($imgs);
    }

    /**
     * ѹ��ͼƬ
     * @param string $url ͼƬ��ַ
     * @param string $path ��ŵ�Ŀ¼
     * @return string
     */
    public function reSizeImg($url,$path = './'){

        $imgName = $path.uniqid().'.jpg';
        $file    = $url;
        list($width,$height) = getimagesize($file); //��ȡԭͼƬ�ߴ�
        $percent = (100/$width);
        //���ųߴ�
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
     *  ����Բ��
     * @param string $url ͼƬ��ַ
     * @param string $path Ҫ��ŵ�Ŀ¼
     * @return string
     */
    public function createFillet($url,$path = './'){

        $w = 100; $h = 100;  //����ͼƬ����
        $original_path = $url;
        $destPath      = $path.uniqid().'.png';    //����ͼƬ·��
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
     * @param array $imgs ��Ҫ�ϲ���ͼƬ��
     * @param string $path ���·��Ŀ¼
     * @return string
     */
    public function mergeImg($imgs,$path = '.'){

        $imgName = $path.rand(1000,9999).uniqid().'.jpg';  //ͼƬ����
        list($width,$height) = getimagesize($imgs['dst']);   //��ȡͼƬ���
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
<?php
error_reporting(E_ALL);
define('SITE_PATH', getcwd());
$big_img = SITE_PATH.'/data/uploads/avatar/2/original.jpg';
echo $big_img;
echo '<br>';
$width = '400';
$height = '300';
$small_img = SITE_PATH.'/data/uploads/avatar/2/image22.jpg';
echo $small_img;
echo '<br>';
echo SITE_PATH.'/addons/libs/Image.class.php';
include('Image.class.php' );
Image::thumb( $big_img, $small_img , '' , 300 , 300 );
echo '<br> end!!!';
/*
$imgage = getimagesize($big_img); //得到原始大图片
switch ($imgage[2]) { // 图像类型判断
    case 1:
        $im = imagecreatefromgif($big_img);
        break;
    case 2:
        $im = imagecreatefromjpeg($big_img);
        break;
    case 3:
        $im = imagecreatefrompng($big_img);
        break;
}
$src_W = $imgage[0]; //获取大图片宽度
$src_H = $imgage[1]; //获取大图片高度
$tn = imagecreatetruecolor($width, $height); //创建缩略图
imagecopyresampled($tn, $im, 0, 0, 0, 0, $width, $height, $src_W, $src_H); //复制图像并改变大小
imagejpeg($tn, $small_img); //输出图像
*/

?>

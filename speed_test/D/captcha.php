<?php
session_start();
header("Content-type: image/png");

$_SESSION["captcha"] = "";

function getCaptcha($countCaptcha, $captchaImage, $fontCaptcha, $fontSizeCaptcha, $positionCaptcha, $colorCaptcha) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
 
    for ($i = 1; $i <= $countCaptcha; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];

	    $rotateCaptcha = rand(0, 20);
        $spacingCaptcha = 8 + 50 * $i;
        imagettftext($captchaImage, $fontSizeCaptcha, $rotateCaptcha, $spacingCaptcha, $positionCaptcha, $colorCaptcha, $fontCaptcha, $characters[$index]);	
    }
 
    return $randomString;
}

$sizeCaptchaX = 300;
$sizeCaptchaY = 50;
$size = 50;
$captchaImage = imagecreatetruecolor($sizeCaptchaX, $sizeCaptchaY);
imagecolorallocate($captchaImage, 0, 0, 0);

for($y = 0; $y < $sizeCaptchaY; $y += $size){
  for($x = 0; $x < $sizeCaptchaX; $x += $size){
   $r = rand(0,255);
   $g = rand(0,255);
   $b = rand(0,255);

   $color = imagecolorallocate($captchaImage, $r, $g, $b);
  
   imagefilledrectangle($captchaImage, $x, $y, $x+$size ,$y+$size, $color);

    $white = imagecolorallocate($captchaImage, 255, 255, 255);
    imageline($captchaImage, $x, $y, $r, $g, $white);
 
  }
}    

$colorCaptcha = imagecolorallocate($captchaImage, 253, 252, 252);
$fontCaptcha = "./Poppins-ExtraBold.ttf"; 
$fontSizeCaptcha = 20;
$positionCaptcha = 37;
$countCaptcha = 4;


$captchaCode = getCaptcha($countCaptcha, $captchaImage, $fontCaptcha, $fontSizeCaptcha, $positionCaptcha, $colorCaptcha);
$_SESSION["captcha"] = $captchaCode;

imagepng($captchaImage); 
imagedestroy($captchaImage);

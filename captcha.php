<?php
session_start();
$permitted_chars = '0123456789';

function generate_string($input, $strength = 10)
{
    $input_length = strlen($input);
    $random_string = '';
    for ($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
    $_SESSION['captcha_text'] = $random_string;
    return $random_string;
}
$image = imagecreatetruecolor(200, 200);
imageantialias($image, true);
// $colors = [];
$opacity = 0.5;
$transparency = 1-$opacity;

imagefill($image, 0, 0, 75);

$amount = rand(0, 8);

for ($i = 0; $i < $amount; $i++) {
    imagesetthickness($image, rand(2, 10));
    $size = rand(10, 100);
    $green = rand(0,255);
    $red = rand(0,255);
    $blue = rand(0,255);
    // img, r, g, b, a
    $line_color = imagecolorallocatealpha($image, $green, $red, $blue, 127*$transparency);
    // img, center_x, center_y, width, height, color
    imagefilledellipse($image, rand(50, 150), rand(50, 150), $size, $size, $line_color);
}

$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
$textcolors = [$black, $white];
$fonts = [dirname(__FILE__) . '\static\fonts\Lato-Regular.ttf'];
$string_length = 6;
$captcha_string = generate_string($permitted_chars, $string_length);
for ($i = 0; $i < $string_length; $i++) {
    $letter_space = 170 / $string_length;
    $initial = 15;
    //img, size, angle, x, y, color, font, content
    imagettftext($image, 24, rand(-15, 15), $initial + $i * $letter_space, rand(55, 165), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_string[$i]);
}
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);

#!/usr/bin/env php
<?PHP


require("logo.php");

logo( 5001, "chey.png", [0, 0, 0] );
logo( 5001, "sharon.png", [0xE3, 0xA6, 0xEC] );



$im_x = 1600;
$im_y = 900;

$logo_x = 600;
$logo_y = 600;

$img = imagecreate( $im_x, $im_y );

$gray = imagecolorallocate( $img, 128, 128, 128 );

# $lilac = imagecolorallocate( $img, 0xE3, 0xA6, 0xEC );

imagefill( $img, 0, 0, $gray );

$logo = imagecreatefrompng("chey.png");

imagecopyresampled( $img, $logo,
	($im_x / 2) - ($logo_x / 2), ($im_y / 2) - ($logo_y / 2),
	0, 0,
	$logo_x, $logo_y,
	imagesx($logo), imagesy($logo) );

imagepng( $img, "desktop(hires).png", 0);

exit(0);

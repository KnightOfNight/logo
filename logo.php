<?PHP

$thick_width = .20;
$thin_width = .10;

function logo($size = NULL, $file = NULL, array $fg_color_a = NULL, array $bg_color_a = NULL, $bg_transparent = true) {
	global $thick_width;
	global $thin_width;

	if (is_null($size) OR (! is_int($size)) OR ($size < 100)) {
		$size = 100;
	}

	if (is_null($file)) {
		$file = "logo.png";
	}

	if (is_null($fg_color_a)) {
		$fg_color_a = [0, 0, 0];
	}

	if (is_null($bg_color_a)) {
		$bg_color_a = [255, 255, 255];
	}

	$max_xy = $size - 1;

	$xy_ratio = $max_xy / ($max_xy - ($max_xy * $thick_width));


	$img = imagecreate($size, $size);
	$bg_color = imagecolorallocate($img, $bg_color_a[0], $bg_color_a[1], $bg_color_a[2]);
	$fg_color = imagecolorallocate($img, $fg_color_a[0], $fg_color_a[1], $fg_color_a[2]);
#	imagefill($img, 0, 0, $bg_color);


	$sx = 0;
	$sy = 0;
	$ex = $max_xy - ($max_xy * $thick_width);
	$ey = $max_xy;
	imageline($img, $sx, $sy, $ex, $ey, $fg_color);

	$sx = $max_xy * $thick_width;
	$sy = 0;
	$ex = $max_xy;
	$ey = $max_xy;
	imageline($img, $sx, $sy, $ex, $ey, $fg_color);

	$sx = $max_xy * $thick_width * .5;
	$sy = 0;
	imagefill($img, $sx, $sy, $fg_color);


	$sx = 0;
	$sy = $max_xy;
	$ex = $max_xy - ($max_xy * $thick_width);
	$ey = 0;
	imageline($img, $sx, $sy, $ex, $ey, $fg_color);

	$sx = $max_xy * $thick_width;
	$sy = $max_xy;
	$ex = $max_xy;
	$ey = 0;
	imageline($img, $sx, $sy, $ex, $ey, $fg_color);

	$sx = $max_xy - ($max_xy * $thick_width * .5);
	$sy = 0;
	imagefill($img, $sx, $sy, $fg_color);

	$sx = $max_xy * $thick_width * .5;
	$sy = $max_xy;
	imagefill($img, $sx, $sy, $fg_color);


	$sx = ($max_xy * .5) - ($max_xy * $thick_width);
	$sy = $max_xy * .5;
	$ex = 0;
	$ey = $sy - ($sx * $xy_ratio);
	imageline($img, $sx, $sy, $ex, $ey, $fg_color);

	$ey = $sy + ($sx * $xy_ratio);
	imageline($img, $sx, $sy, $ex, $ey, $fg_color);


	$sx = ($max_xy * .5) - ($max_xy * $thick_width) - ($max_xy * $thin_width);
	$sy = $max_xy * .5;
	$ex = 0;
	$ey = $sy - ($sx * $xy_ratio);
	imageline($img, $sx, $sy, $ex, $ey, $fg_color);

	$ey = $sy + ($sx * $xy_ratio);
	imageline($img, $sx, $sy, $ex, $ey, $fg_color);


	$sx = ($max_xy * .5) - ($max_xy * $thick_width) - ($max_xy * $thin_width * .5);
	$sy = $max_xy * .5;
	imagefill($img, $sx, $sy, $fg_color);


	if ($bg_transparent) {
		imagecolortransparent($img, $bg_color);
	}


	imagepng($img, $file, 0);

}

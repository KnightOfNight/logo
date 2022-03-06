#!/usr/bin/env php -d memory_limit=256M
<?PHP

error_reporting(E_ALL ^ E_DEPRECATED);

require("logo.php");

function usage() {
    printf("Usage: desktop.php --width <int> --height <int> --logo-scale <float> [--help]\n");
}

function desktop($img_x, $img_y, $logo_scale = .5, $bk_r = 85, $bk_g = 85, $bk_b = 85, $logo_r = 0, $logo_g = 0, $logo_b = 0) {
    if (! is_int($img_x) or ($img_x < 100)) {
        printf("FATAL: invalid width, must be integer >= 100\n");
        exit(1);
    } elseif (! is_int($img_y) or ($img_y < 100)) {
        printf("FATAL: invalid height, must be integer >= 100\n");
        exit(1);
    } elseif (! is_float($logo_scale) or ($logo_scale < .1 or $logo_scale > 1)) {
        printf("FATAL: invalid logo scale, must be floating point number between .1 and 1\n");
        exit(1);
    }

    $output_file = sprintf("desktop-%dx%dx%d%%", $img_x, $img_y, (int) ($logo_scale * 100));
    
    $logo_size = (int) ($img_y * $logo_scale);
    
    $logo_color = array($logo_r, $logo_g, $logo_b);
    
    $logo = logo($logo_size, NULL, $logo_color);
    
    $desktop = imagecreate($img_x, $img_y);
    
    $background_color = imagecolorallocate($desktop, $bk_r, $bk_g, $bk_b);
    
    imagefill($desktop, 0, 0, $background_color);
    
    $logo_start_x = ($img_x / 2) - ($logo_size / 2);
    $logo_start_y = ($img_y / 2) - ($logo_size / 2);
    
    imagecopy($desktop, $logo, $logo_start_x, $logo_start_y, 0, 0, $logo_size, $logo_size);
    
    imagejpeg($desktop, $output_file . ".jpg", 100);
    imagepng($desktop, $output_file . ".png");
}

$options = getopt(NULL, array("width:", "height:", "logo-scale:", "help"));

if (isset($options["help"])) {
    usage();
    exit(0);
} elseif (! isset($options["width"]) or ! isset($options["height"]) or ! isset($options["logo-scale"])) {
    printf("FATAL: missing required argument\n\n");
    usage();
    exit(1);
}

$width = (int) $options["width"];
$height = (int) $options["height"];
$scale = (float) $options["logo-scale"];

desktop($width, $height, $scale);

exit(0);

#!/usr/bin/env php -d memory_limit=256M
<?PHP

error_reporting(E_ALL ^ E_DEPRECATED);

require("logo.php");

function usage() {
    $msg = 
    printf("Usage: wallpaper.php [options]
--width <int>        Width of the final image in pixels: (100 or greater)
--height <int>       Height of the final image in pixels:  (100 or greater)
--logo-scale <float> Scale of the logo relative to the final image size: (.1 - 1.0)
--bg-r <int>         Background color: red value (0-255)
--bg-g <int>         Background color: green value (0-255)
--bg-b <int>         Background color: blue value (0-255)
--logo-r <int>       Logo color: red value (0-255)
--logo-g <int>       Logo color: green value (0-255)
--logo-b <int>       Logo color: blue value (0-255)
--type <str>         File type: png or bmp\n");
}

function wallpaper($width, $height, $logo_scale = .5, $bg_r = 85, $bg_g = 85, $bg_b = 85, $logo_r = 0, $logo_g = 0, $logo_b = 0, $file_type = "png") {
    if (! is_int($width) or $width < 100 or ! is_int($height) or $height < 100) {
        printf("FATAL: width and height must be integer >= 100\n");
        exit(2);
    } elseif (! is_float($logo_scale) or $logo_scale < .1 or $logo_scale > 1) {
        printf("FATAL: invalid logo scale, must be a float >= .1 and <= 1\n");
        exit(2);
    } else {
        $colors = array($bg_r, $bg_g, $bg_b, $logo_r, $logo_g, $logo_b);
        foreach ($colors as $color) {
            if (! is_int($color) or $color < 0 or $color > 255) {
                printf("FATAL: all colors must be an integer between >= 0 and <= 255\n");
                exit(2);
            }
        }
    }

    $output_file = sprintf("wallpaper-%dx%dx%d%%x%d-%d-%dx%d-%d-%d", $width, $height, (int) ($logo_scale * 100), $bg_r, $bg_g, $bg_b, $logo_r, $logo_g, $logo_b);
    
    $logo_size = (int) ($height * $logo_scale);
    if ($logo_size < 50) {
        $logo_size = 50;
    }
    
    $logo_color = array($logo_r, $logo_g, $logo_b);
    
    $logo = logo($size = $logo_size, $file = NULL, $fg_color_a = $logo_color);
    
    $wallpaper = imagecreate($width, $height);
    
    $background_color = imagecolorallocate($wallpaper, $bg_r, $bg_g, $bg_b);
    
    imagefill($wallpaper, 0, 0, $background_color);
    
    $logo_start_x = ($width / 2) - ($logo_size / 2);
    $logo_start_y = ($height / 2) - ($logo_size / 2);
    
    imagecopy($wallpaper, $logo, $logo_start_x, $logo_start_y, 0, 0, $logo_size, $logo_size);
    
    if ($file_type == "png") {
        imagepng($wallpaper, $output_file . ".png");
    } elseif ($file_type == "bmp") {
        imagebmp($wallpaper, $output_file . ".bmp");
    } else {
        printf("FATAL: invalid file type '%s'\n", $file_type);
        exit(2);
    }
#    imagejpeg($wallpaper, $output_file . ".jpg", 100);
}

$generate_options = array(
    "width:" => array(
        "required" => True,
        "type" => "int",
        "default" => NULL,
    ),
    "height:" => array(
        "required" => True,
        "type" => "int",
        "default" => NULL,
    ),
    "logo-scale:" => array(
        "required" => False,
        "type" => "float",
        "default" => .5,
    ),
    "bg-r:" => array(
        "required" => False,
        "type" => "int",
        "default" => 65,
    ),
    "bg-g:" => array(
        "required" => False,
        "type" => "int",
        "default" => 65,
    ),
    "bg-b:" => array(
        "required" => False,
        "type" => "int",
        "default" => 65,
    ),
    "logo-r:" => array(
        "required" => False,
        "type" => "int",
        "default" => 0,
    ),
    "logo-g:" => array(
        "required" => False,
        "type" => "int",
        "default" => 0,
    ),
    "logo-b:" => array(
        "required" => False,
        "type" => "int",
        "default" => 0,
    ),
    "type:" => array(
        "required" => False,
        "type" => "str",
        "default" => "png",
    ),
);

$help_option = array("help" => NULL);

$all_options = array_merge($generate_options, $help_option);

$parsed_options = getopt(NULL, array_keys($all_options));

if (isset($parsed_options["help"])) {
    usage();
    exit(0);
}

foreach ($generate_options as $key => $details) {
    $option = str_replace(":", "", $key);

    $required = $details["required"];
    $type = $details["type"];
    $default = $details["default"];

    if (isset($parsed_options[$option])) {
        if ($type == "int" or $type == "float") {
            if (! is_numeric($parsed_options[$option])) {
                printf("FATAL: --%s value '%s' is invalid type, expecting %s\n\n", $option, $parsed_options[$option], $type);
                usage();
                exit(1);
            }
        } elseif ($type != "str") {
            printf("FATAL: configuration error, '%s' has invalid type '%s'\n", $option, $type);
            exit(2);
        }
    } else {
        if ($required) {
            printf("FATAL: --%s is required\n\n", $option);
            usage();
            exit(1);
        } else {
            $parsed_options[$option] = $default;
        }
    }
}

$width = (int) $parsed_options["width"];
$height = (int) $parsed_options["height"];
$logo_scale = (float) $parsed_options["logo-scale"];
$bg_r = (int) $parsed_options["bg-r"];
$bg_g = (int) $parsed_options["bg-g"];
$bg_b = (int) $parsed_options["bg-b"];
$logo_r = (int) $parsed_options["logo-r"];
$logo_g = (int) $parsed_options["logo-g"];
$logo_b = (int) $parsed_options["logo-b"];
$type = $parsed_options["type"];

wallpaper($width, $height, $logo_scale=$logo_scale, $bg_r=$bg_r, $bg_g=$bg_g, $bg_b=$bg_b, $logo_r=$logo_r, $logo_g=$logo_g, $logo_b=$logo_b, $file_type=$type);

exit(0);

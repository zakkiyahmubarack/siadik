<?php

session_start();



require "_config.php";



$line_colors = preg_split("/,\s*?/", CODE_LINE_COLORS);

$char_colors = preg_split("/,\s*?/", CODE_CHAR_COLORS);

$fonts = collect_files(PATH_TTF, "ttf");



$img = imagecreatetruecolor(CODE_WIDTH, CODE_HEIGHT);

imagefilledrectangle($img, 0, 0, CODE_WIDTH - 1, CODE_HEIGHT - 1, gd_color(CODE_BG_COLOR));





// Draw lines



for ($i = 0; $i < CODE_LINES_COUNT; $i++)

    imageline($img,

        rand(0, CODE_WIDTH - 1),

        rand(0, CODE_HEIGHT - 1),

        rand(0, CODE_WIDTH - 1),

        rand(0, CODE_HEIGHT - 1),

        gd_color($line_colors[rand(0, count($line_colors) - 1)])

    );





// Draw code



$code = "";

$y = (CODE_HEIGHT / 2) + (CODE_FONT_SIZE / 2);

for ($i = 0; $i < CODE_CHARS_COUNT; $i++) {

    $color = gd_color($char_colors[rand(0, count($char_colors) - 1)]);

    $angle = rand(-30, 30);

    $char = substr(CODE_ALLOWED_CHARS, rand(0, strlen(CODE_ALLOWED_CHARS) - 1), 1);

    $font = PATH_TTF . "/" . $fonts[rand(0, count($fonts) - 1)];

    $x = (intval((CODE_WIDTH / CODE_CHARS_COUNT) * $i) + (CODE_FONT_SIZE / 2));

    $code .= $char;

    imagettftext($img, CODE_FONT_SIZE, $angle, $x, $y, $color, $font, $char);

}



$_SESSION['__img_code__'] = md5($code);



header("Content-Type: image/png");

imagepng($img);

imagedestroy($img);





function gd_color($html_color) {

    return preg_match('/^#?([\dA-F]{6})$/i', $html_color, $rgb)

      ? hexdec($rgb[1]) : false;

}





function collect_files($dir, $ext) {

    if (false !== ($dir = opendir($dir))) {

        $files = array();



        while (false !== ($file = readdir($dir)))

            if (preg_match("/\\.$ext\$/i", $file))

                $files[] = $file;



        return $files;



    } else

        return false;

}



?>
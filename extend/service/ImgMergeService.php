<?php


namespace service;


class ImgMergeService
{
//    public function getPic()
//    {
//
//        header('Content-Type: text/html; charset=utf-8');
//
//        $text = '中粮屯河（sh600737）';//中粮屯河（sh600737）
//
//        $watermark = '305988103123zczcxzas';
//
//        $len = strlen($text);
//
//        $width = 10.5 * (($len - 8) / 3 * 2 + 8);
//
//        $height = 26;
//
//        $imagick = new Imagick();
//
//        $color_transparent = new ImagickPixel('#ffffff'); //transparent 透明色
//
//        $imagick->newImage($width, $height, $color_transparent, 'jpg');
//
//        //$imagick->borderimage('#000000', 1, 1);
//
//        $style['font_size'] = 12;
//
//        $style['fill_color'] = '#000000';
//
//        for ($num = strlen($watermark); $num >= 0; $num--) {
//
//            $this->add_text($imagick, substr($watermark, $num, 1), 2 + ($num * 8), 30, 1, $style);
//
//            $this->add_text($imagick, substr($watermark, $num, 1), 2 + ($num * 8), 5, 1, $style);
//
//        }
//
//        //return;
//
//        $style['font_size'] = 20;
//
//        $style['fill_color'] = '#FF0000';
//
//        $style['font'] = './msyh.ttf'; ///微软雅黑字体 解决中文乱码
//
//        //$text=mb_convert_encoding($text,'UTF-8'); //iconv("GBK","UTF-8//IGNORE",$text);
//
//        $this->add_text($imagick, $text, 2, 20, 0, $style);
//
//        header('Content-type: ' . strtolower($imagick->getImageFormat()));
//
//        echo $imagick->getImagesBlob();
//
//    }

    public static function getImagick($url) {
        return new \Imagick($url);
    }
    // 添加水印文字
    public static function addText(\Imagick &$imagick, $text, $x = 0, $y = 0, $angle = 0, $style = array())
    {

        $draw = new \ImagickDraw ();

        if (isset ($style ['font']))

            $draw->setFont($style ['font']);

        if (isset ($style ['font_size']))

            $draw->setFontSize($style ['font_size']);

        if (isset ($style ['fill_color']))

            $draw->setFillColor($style ['fill_color']);

        if (isset ($style ['under_color']))

            $draw->setTextUnderColor($style ['under_color']);

        if (isset ($style ['font_family']))

            $draw->setfontfamily($style ['font_family']);

        if (isset ($style ['font']))

            $draw->setfont($style ['font']);

        $draw->settextencoding('UTF-8');

        if (strtolower($imagick->getImageFormat()) == 'gif') {

            foreach ($imagick as $frame) {

                $frame->annotateImage($draw, $x, $y, $angle, $text);

            }

        } else {
            $imagick->annotateImage($draw, $x, $y, $angle, $text);

        }
        $imagick->setImageFormat('png');
    }
}
<?php

namespace App\Format\Substring;

class ArticleContent
{
    public static function substr($content){
        $content=preg_replace('/----/m',"<br>",$content);
        $content=str_replace(array("#", "-" ," ","　",), "", $content);
        $content=mb_substr($content ,0,300,'utf-8');
        return $content;
    }
}
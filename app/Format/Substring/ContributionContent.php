<?php

namespace App\Format\Substring;

class ContributionContent
{
    public static function substr($content){
        $content=preg_replace('/(\\r\\n)+/m','<br>',$content);
        $content=mb_substr($content ,0,100,'utf-8');
        return $content;
    }
}
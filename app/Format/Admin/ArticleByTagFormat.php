<?php

namespace App\Format\Admin;

use App\Format\Format;

class ArticleByTagFormat extends Format
{
    public function format($item)
    {
        return [
            'id' => $item['id'],
            'title' => $item['title'],
            'image' => $item['image'],
            'source' => $item['source'],
            'reading' => $item['reading'],
            'tag_name' => $item['tag_name'],
            'created_at' => $item['created_at'],
            'updated_at' => $item['updated_at'],
        ];
    }
}
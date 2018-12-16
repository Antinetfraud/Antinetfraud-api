<?php

namespace App\Format;

use App\Format\Substring\ArticleContent;

class ArticleFormat extends Format
{
    public function format($item)
    {
        return [
            'id' => $item['id'],
            'title' => $item['title'],
            'image' => $item['image'],
            'source' => $item['source'],
            'content' => ArticleContent::substr($item['content']),
            'tag_id' => $item['tag_id'],
            'tag_name' => $item['tag']['name'],
            'created_at' => date('Y-m-d', strtotime($item['created_at'])),
        ];
    }
}
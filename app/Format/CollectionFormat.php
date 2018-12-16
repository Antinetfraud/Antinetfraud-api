<?php

namespace App\Format;

class CollectionFormat extends Format
{
    public function format($item)
    {
        return [
            'id' => $item['id'],
            'user_id' => $item['user_id'],
            'article_id' => $item['article_id'],
            'title' => $item['article']['title'],
            'image' => $item['article']['image'],
            'tag' => $item['article']['tag_name'],
            'tag_id' => $item['article']['tag_id'],
            'source' => $item['article']['source'],
            'created_at' => $item['created_at'],
        ];
    }
}
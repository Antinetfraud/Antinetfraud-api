<?php

namespace App\Format\Admin;

use App\Format\Format;

class CommentFormat extends Format
{
    public function format($item)
    {
        return [
            'id' => $item['id'],
            'user_name' => $item['user']['name'],
            'article_id' => $item['article_id'],
            'content' => $item['content'],
            'author_reply' => $item['author_reply'],
            'pass' => $item['pass'] ? true : false,
            'created_at' => $item['created_at'],
            'updated_at' => $item['updated_at'],
        ];
    }
}
<?php

namespace App\Format;

class CommentFormat extends Format
{
    public function format($item)
    {
        return [
            'id' => $item['id'],
            'user_name' => $item['user']['name'],
            'content' => $item['content'],
            'author_reply' => $item['author_reply'],
            'created_at' => date('Y-m-d', strtotime($item['created_at'])),
        ];
    }
}
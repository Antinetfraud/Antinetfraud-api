<?php
/**
 * Created by PhpStorm.
 * User: edward
 * Date: 2/3/18
 * Time: 12:53 AM
 */

namespace App\Format;


class BrowserHistoryFormat extends Format
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
            'updated_at' => $item['updated_at'],
        ];
    }
}
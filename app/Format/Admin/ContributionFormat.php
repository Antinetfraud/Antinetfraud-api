<?php

namespace App\Format\Admin;

use App\Format\Format;
use App\Format\Substring\ContributionContent;

class ContributionFormat extends Format
{
    public function format($item)
    {
        return [
            'id' => $item['id'],
            'title' => $item['title'],
            'content' => ContributionContent::substr($item['content']),
            'type' => $item['type']?'电信诈骗':'网络诈骗',
            'deleted_at' => date('Y-m-d', strtotime($item['deleted_at'])),
            'created_at' => date('Y-m-d', strtotime($item['created_at'])),
        ];
    }
}
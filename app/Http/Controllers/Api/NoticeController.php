<?php

namespace App\Http\Controllers\Api;

use Parsedown;
use App\Model\Notice;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class NoticeController extends ApiController
{
    public function all()
    {
        $notices = Notice::latest('created_at')
            ->select(['id', 'title', 'content','created_at'])
            ->paginate(6);
        if ($notices->isEmpty()) {
            return $this->dataNotFound();
        } else {
            $parseDown = new Parsedown();
            foreach ($notices as $notice){
                $notice->content = $parseDown->text($notice->content);
            }
            return $this->responseJson(['notices' => $notices]);
        }
    }

    public function show($id)
    {
        $notice = Notice::find($id);
        if ($notice == null) {
            return $this->dataNotFound();
        } else {
            $parseDown = new Parsedown();
            $notice->content = $parseDown->text($notice->content);
            return $this->responseJson(['notice' => $notice]);
        }
    }
}

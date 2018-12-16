<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class QuestionController extends ApiController
{
    public function store()
    {
//        return $excel->download($export, 'G:/题库/单选题.xlsx');
        $filePath = public_path('单选题.xlsx');
        Excel::load($filePath, function ($reader) {
            $datas = $reader->all()->toArray();
            foreach ($datas as &$data) {
                $data['created_at'] = Carbon::now();
                $data['updated_at'] = Carbon::now();
//                dd($data);
            }
//            dd($datas);
            DB::table('questions')->insert($datas);
            echo 'success!';
        });
    }

    public function all()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }

    public function delete()
    {

    }
}

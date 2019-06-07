<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-06-07
 * Time: 20:59
 */

namespace App\Admin\Controllers;


use App\Http\Controllers\Controller;
use Faker\Provider\File;
use Illuminate\Http\Request;

class UploadController extends Controller
{

    public function image(Request $request)
    {
        $path = $request->file('image')->store("/uploads/images/",'admin');
        return json_encode([

            // errno 即错误代码，0 表示没有错误。
            //       如果有错误，errno != 0，可通过下文中的监听函数 fail 拿到该错误码进行自定义处理
            "errno" =>  0,

            // data 是一个数组，返回若干图片的线上地址
            "data" =>  [
                env('APP_URL')."/".$path
            ]
        ]);
    }
}
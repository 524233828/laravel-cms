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
        var_dump($request->getContent());exit;
        $path = $request->file('avatar')->store("/",'admin');
        var_dump($path);
    }
}
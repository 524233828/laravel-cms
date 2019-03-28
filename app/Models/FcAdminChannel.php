<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-03-28
 * Time: 10:29
 */

namespace App\Models;


use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Model;

class FcAdminChannel extends Model
{

    public function admin()
    {
        return $this->belongsTo(Administrator::class);
    }
}
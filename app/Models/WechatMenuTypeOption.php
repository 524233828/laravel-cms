<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/1/20
 * Time: 9:08
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WechatMenuTypeOption extends Model
{

    public function types()
    {
        return $this->belongsTo(WechatMenuType::class, "type");
    }
}
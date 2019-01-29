<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/1/19
 * Time: 23:44
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WechatMenuType extends Model
{
    public function levels()
    {
        return $this->belongsToMany(WechatMenuLevel::class, "wechat_menu_level_types","type_id","level_id");
    }
}
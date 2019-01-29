<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/1/19
 * Time: 22:28
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WechatMenuLevel extends Model
{
    public function types(){
        return $this->belongsToMany(WechatMenuType::class, "wechat_menu_level_types", "level_id", "type_id");
    }
}
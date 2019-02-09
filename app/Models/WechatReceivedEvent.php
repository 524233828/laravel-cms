<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/2/8
 * Time: 22:33
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WechatReceivedEvent extends Model
{
    public function account()
    {
        return $this->belongsTo(WechatOfficialAccount::class, "wx_app_id", "wx_app_id");
    }

}
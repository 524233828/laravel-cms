<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/2/4
 * Time: 16:29
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WechatReceivedText extends Model
{
    public function account()
    {
        return $this->belongsTo(WechatOfficialAccount::class, "wx_app_id", "wx_app_id");
    }
}
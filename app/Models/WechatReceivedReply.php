<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/2/4
 * Time: 23:12
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WechatReceivedReply extends Model
{
    public function types()
    {
        return $this->belongsTo(WechatUserEventType::class, "type");
    }

    public function receiver()
    {
        return $this->belongsTo(WechatReceivedText::class, "received_id");
    }
}
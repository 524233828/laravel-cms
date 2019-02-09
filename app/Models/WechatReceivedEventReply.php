<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/2/8
 * Time: 23:09
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WechatReceivedEventReply extends Model
{
    public function types()
    {
        return $this->belongsTo(WechatUserEventType::class, "type");
    }

    public function receiver()
    {
        return $this->belongsTo(WechatReceivedEvent::class, "received_id");
    }
}
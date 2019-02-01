<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/2/1
 * Time: 11:47
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WechatUserEvent extends Model
{
    public $primaryKey = "msgid";
    public $keyType = "string";
    public $incrementing = false;

    const CREATED_AT = "received_at";

    public function account()
    {
        return $this->belongsTo(WechatOfficialAccount::class, "to_user_name", "original_id");
    }
}
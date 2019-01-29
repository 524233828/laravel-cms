<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/1/18
 * Time: 10:16
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WechatOfficialAccount extends Model
{

    protected $primaryKey = "wx_app_id";
    public $incrementing = false;
    protected $keyType = "string";

    public function apps()
    {
        return $this->belongsTo(App::class, "app_id");
    }
}
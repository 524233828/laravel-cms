<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/3/23
 * Time: 18:33
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FcOrder extends Model
{
    protected $table = "fc_order";

    const CREATED_AT = "create_time";
//    const UPDATED_AT = "pay_time";

    protected $dateFormat = "U";

//    public function user_forecast(){
//        return $this->hasOne();
//    }

    public function channels()
    {
        return $this->belongsTo(FcChannel::class, "channel", "channel");
    }

    public function extra(){
        return $this->hasOne(FcOrderExtra::class, "order_id", "order_id");
    }
}
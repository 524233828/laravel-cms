<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/3/23
 * Time: 19:24
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FcUserForecast extends Model
{

    protected $table = "fc_user_forecast";

    public function order()
    {
        return $this->hasOne(FcOrder::class, "order_id", "order_id");
    }

    public function forecast(){
        return $this->hasOne(FcForecast::class, "id", "order_id");
    }
}
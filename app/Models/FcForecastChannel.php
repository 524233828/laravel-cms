<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/3/23
 * Time: 18:14
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FcForecastChannel extends Model
{

    protected $table = "fc_forecast_channel";

    const CREATED_AT = "create_time";
    const UPDATED_AT = "update_time";

    protected $dateFormat = "U";

    public function forecast()
    {
        return $this->belongsTo(FcForecast::class, "forecast_id");
    }

    public function channels()
    {
        return $this->belongsTo(FcChannel::class, "channel", "channel");
    }
}
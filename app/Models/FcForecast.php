<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/3/23
 * Time: 18:06
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FcForecast extends Model
{

    protected $table = "fc_forecast";

    const CREATED_AT = "create_time";
    const UPDATED_AT = "update_time";

    protected $dateFormat = "U";

}
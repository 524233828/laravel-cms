<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/3/23
 * Time: 17:12
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FcChannel extends Model
{
    protected $table = "fc_channel";

//    public $timestamps = false;
    const CREATED_AT = "create_time";
    const UPDATED_AT = "update_time";

    protected $dateFormat = "U";

    public function parent()
    {
        return $this->belongsTo(FcChannel::class, "parent_id");
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-05-11
 * Time: 09:50
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CmsChapter extends Model
{

    public function types(){
        return $this->belongsTo(CmsChapterType::class, "type");
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/10/9
 * Time: 11:28
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ChapterModel extends Model
{

    protected $table = "chapter";

    protected $fillable = [
        "title",
        "content"
    ];
}
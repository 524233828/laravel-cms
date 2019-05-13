<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-05-11
 * Time: 09:49
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CmsChapterType extends Model
{

    public static function getType()
    {
        $types = CmsChapterType::all();

        $data = [];
        foreach ($types as $type)
        {
            $data[$type['id']] = $type['name'];
        }

        return $data;
    }
}
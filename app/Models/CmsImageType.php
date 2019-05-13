<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-05-11
 * Time: 10:03
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CmsImageType extends Model
{
    public static function getType()
    {
        $types = CmsImageType::all();

        $data = [];
        foreach ($types as $type)
        {
            $data[$type['id']] = $type['name'];
        }

        return $data;
    }
}
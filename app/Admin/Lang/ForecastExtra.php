<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/3/23
 * Time: 20:59
 */

namespace App\Admin\Lang;


class ForecastExtra
{

    public static $lang = [
        "name" => "姓名",
        "gender" => "性别",
        "birthday" => "出生年月",
        "other_name" => "对方姓名",
        "other_birthday" => "对方出生年月",
        "other_gender" => "对方性别",
        "female" => "女",
        "male" => "男"
    ];

    public static function translate($word)
    {
        return isset(self::$lang[$word]) ? self::$lang[$word] : $word;
    }
}
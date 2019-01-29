<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/1/25
 * Time: 20:25
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;

class WechatMenuConfig extends Model
{

    public $incrementing = false;
    protected $keyType = "string";

    public function paginate()
    {
        $menu_id = Request::segment(3);

        $menu = WechatMenu::where("id", $menu_id)->first();

        $type = $menu->type;

        $options = WechatMenuTypeOption::where("type",$type)->get()->toArray();

        $menu_configs = WechatMenuConfig::where("menu_id", $menu_id)->get()->toArray();

        $config = [];
        foreach ($menu_configs as $menu_config)
        {
            $config[$menu_config['key']] = ["id" => $menu_config['id'],"value" => $menu_config['value']];
        }

        foreach($options as &$option)
        {
            if(isset($config[$option['key']]))
            {
                $option['id'] = "config_".$config[$option['key']]['id'];
                $option['value'] = $config[$option['key']]['value'];
            }else{
                $option['id'] = "option_".$option['id'];
                $option['value'] = "未配置";
            }
        }


        $movies = static::hydrate($options);

        $paginator = new LengthAwarePaginator($movies, 1, 1);

        $paginator->setPath(url()->current());

        return $paginator;

    }

    public static function with($relations)
    {
        return new static;
    }
}
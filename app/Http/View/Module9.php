<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-05-08
 * Time: 18:06
 */

namespace App\Http\View;


use App\Cms\AbstractViewable;
use App\Models\CmsChapter;

class Module9 extends AbstractViewable
{

    protected $view = "web.module-9";

    protected $css = ["css/web/module_9.css",];

    protected $js = ["https://webapi.amap.com/maps?v=1.4.14&key=28522f036544986e1ad0a6e1ba03e439"];

    protected $is_leaf = true;

    public function __construct()
    {
        $this->addScript($this->script());
    }

    protected function getChapter()
    {
        $chapters = CmsChapter::where([["status","=","3"],["type","=", "2"]])->orderBy("created_at","DESC")->limit(7)->get()->all();

        return $chapters;

    }

    public function render()
    {
        return view($this->view, ["chapters" => $this->getChapter()]);
        // TODO: Implement render() method.
    }

    protected function script()
    {
        return <<<SCRIPT
        
    let longitude = 113.557456;
    let latitude = 24.796976;
        
    let map = new AMap.Map('map', {
        resizeEnable: true,
        zoom:18,//级别
        center: [113.557456, 24.796976],//中心点坐标
        viewMode:'3D'//使用3D视图
    });

    let marker = new AMap.Marker({
        icon: "//a.amap.com/jsapi_demos/static/demo-center/icons/poi-marker-default.png",
        position: [longitude, latitude]
        // offset: new AMap.Pixel(-13, -30)
    });
    marker.setMap(map);
    
    $("#map").on("click",function(){
        location.href = "https://m.amap.com/navi/?dest="+longitude+","+latitude+"&destName=驾车&key=28522f036544986e1ad0a6e1ba03e439"
    });
SCRIPT;

    }
}
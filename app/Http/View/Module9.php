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
}
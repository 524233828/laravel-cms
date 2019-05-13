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
use App\Models\CmsImage;

class Module1 extends AbstractViewable
{

    protected $view = "web.module-1";

    protected $css = ["css/web/module_1.css",];

    protected $is_leaf = true;

    protected function getImage()
    {
        $images = CmsImage::where([["status","=","1"],["type","=", "2"]])
            ->orderBy("created_at","DESC")->limit(1)->get()->all();
        if(!empty($images))
        {
            return $images[0];
        }

        return ["path" => "images/module-1.png"];
    }

    public function render()
    {
        return view($this->view, ["image" => $this->getImage()]);
    }
}
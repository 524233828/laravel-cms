<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-05-08
 * Time: 18:06
 */

namespace App\Http\View;


use App\Cms\AbstractViewable;
use App\Models\CmsImage;

class Module3 extends AbstractViewable
{

    protected $view = "web.module-3";

    protected $css = ["css/web/module_3.css",];

    protected $is_leaf = true;


    protected function getImage()
    {
        $images = CmsImage::where([["status","=","1"],["type","=", "4"]])
            ->orderBy("created_at","DESC")->limit(3)->get()->all();
        if(empty($images))
        {
            $images = [
                ["path" => "images/module-3.png"],
                ["path" => "images/module-3.png"],
                ["path" => "images/module-3.png"],
            ];
        }

        return $images;
    }

    public function render()
    {
        return view($this->view, ["images" => $this->getImage()]);
        // TODO: Implement render() method.
    }
}
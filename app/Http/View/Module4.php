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

class Module4 extends AbstractViewable
{

    protected $view = "web.module-4";

    protected $css = ["css/web/module_4.css",];

    protected $is_leaf = true;

    protected function getImage()
    {
        $images = CmsImage::where([["status","=","1"],["type","=", "5"]])
            ->orderBy("created_at","DESC")->limit(1)->get()->all();
        if(empty($images))
        {
            $images = [
                ["path" => "images/module-4.png"],
            ];
        }

        return $images;
    }

    public function render()
    {
        return view($this->view, ["images"=>$this->getImage()]);
        // TODO: Implement render() method.
    }
}
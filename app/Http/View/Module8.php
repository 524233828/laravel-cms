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

class Module8 extends AbstractViewable
{

    protected $view = "web.module-8";

    protected $css = ["css/web/module_8.css",];

    protected $is_leaf = true;

    protected function getImage()
    {
        $images = CmsImage::where([["status","=","1"],["type","=", "6"]])->orderBy("created_at","DESC")->limit(2)->get()->all();

        if(empty($images)){
            $images = [
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
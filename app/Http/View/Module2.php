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

class Module2 extends AbstractViewable
{

    protected $view = "web.module-2";

    protected $css = ["css/web/module_2.css",];

    protected $is_leaf = true;

    protected function getChapter()
    {
        $chapters = CmsChapter::where([["status","=","3"],["type","=", "2"]])->orderBy("created_at","DESC")->limit(5)->get()->all();

        /**
         * @var CmsChapter $chapter
         */
        foreach ($chapters as &$chapter){
            $chapter = $chapter->toArray();

            $chapter['created_at'] = date("Y/m/d",strtotime($chapter['created_at']));
        }
//
        return $chapters;

    }

    protected function getImage()
    {
        $images = CmsImage::where([["status","=","1"],["type","=", "3"]])->orderBy("created_at","DESC")->limit(2)->get()->all();

        if(empty($images)){
            $images = [
                ["path" => "images/module-2.png"],
                ["path" => "images/module-2.png"]
            ];
        }
        return $images;
    }


    public function render()
    {
        return view($this->view, [
            "chapters" => $this->getChapter(),
            "images" => $this->getImage()
        ]);
        // TODO: Implement render() method.
    }
}
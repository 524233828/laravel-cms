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

class Module6 extends AbstractViewable
{

    protected $view = "web.module-6";

    protected $css = ["css/web/module_6.css",];

    protected $is_leaf = true;

    protected function getChapter($type = 1)
    {
        $chapters = CmsChapter::where([["status","=","3"],["type","=", $type]])->orderBy("created_at","DESC")->limit(5)->get()->all();

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

    public function render()
    {
        return view($this->view, [
            "chapter1" =>$this->getChapter(1),
            "chapter3" =>$this->getChapter(3),
            "chapter4" =>$this->getChapter(4),
            "chapter5" =>$this->getChapter(5),
        ]);
        // TODO: Implement render() method.
    }
}
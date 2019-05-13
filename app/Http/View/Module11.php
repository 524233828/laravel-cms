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
use App\Models\CmsChapterType;
use function GuzzleHttp\Psr7\parse_query;

class Module11 extends AbstractViewable
{

    protected $view = "web.module-11";

    protected $css = ["css/web/module_11.css"];

    protected $is_leaf = true;

    protected $chapter = 1;

    protected $where = [];

    public function __construct($chapter_id)
    {

        $this->chapter = $chapter_id;
    }

    public function getTitle($type)
    {
        return CmsChapterType::where("id","=",$type)->get()->all();
    }

    public function getChapter($chapter_id)
    {
        return CmsChapter::find($chapter_id);
    }

    public function render()
    {

        $chapter = $this->getChapter($this->chapter);

        return view($this->view, [
            "title" => $this->getTitle($chapter->type)[0]->toArray(),
            "chapter" => $chapter
        ]);
    }
}
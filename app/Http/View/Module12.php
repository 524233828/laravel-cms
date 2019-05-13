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
use App\Models\CmsFile;
use function GuzzleHttp\Psr7\parse_query;

class Module12 extends AbstractViewable
{

    protected $view = "web.module-12";

    protected $css = ["css/web/module_12.css", "css/web/pager.css"];

    protected $is_leaf = true;

    protected $chapter = 1;

    protected $where = [];


    public function getList()
    {
        $where = [["status","=","1"]];
        return CmsFile::where($where)
            ->paginate(18);
    }

    public function getChapter($chapter_id)
    {
        return CmsChapter::find($chapter_id);
    }

    public function filterPager()
    {
        $query = parse_query(request()->getQueryString());
        if(isset($query['page'])){
            unset($query['page']);
        }
        $query = http_build_query($query);

        return $query;
    }

    public function render()
    {

        $pagination = $this->getList();

        $query = $this->filterPager();

        return view($this->view, [
            "pagination" => $pagination,
            "query" => $query,
            "pages" => $pagination->getUrlRange(1,$pagination->lastPage())
        ]);
    }
}
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

class Module10 extends AbstractViewable
{

    protected $view = "web.module-10";

    protected $css = ["css/web/module_10.css","css/web/pager.css"];

    protected $is_leaf = true;

    protected $where = [];
    protected $title = "";

    public function __construct($title, $where = [])
    {

        $this->where = $where;
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getList()
    {
        $where = [["status","=","3"]];
        $where = array_merge($where, $this->where);
        return CmsChapter::where($where)
            ->paginate(8);
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
            "title" => $this->getTitle(),
            "pagination" => $pagination,
            "query" => $query,
            "pages" => $pagination->getUrlRange(1,$pagination->lastPage())

        ]);
    }
}
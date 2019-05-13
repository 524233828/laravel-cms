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

    protected $type = 1;

    protected $where = [];

    public function __construct($type, $where = [])
    {

        $this->type = $type;
        $this->where = $where;
    }

    public function getTitle($type)
    {
        return CmsChapterType::where("id","=",$type)->get()->all();
    }

    public function getList($type)
    {
        $where = [["type","=",$type],["status","=","3"]];
        array_merge($where, $this->where);
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
        $pagination = $this->getList($this->type);
        $query = $this->filterPager();

        return view($this->view, [
            "title" => $this->getTitle($this->type)[0]->toArray(),
            "pagination" => $pagination,
            "query" => $query,
            "pages" => $pagination->getUrlRange(1,$pagination->lastPage())

        ]);
    }
}
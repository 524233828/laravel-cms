<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-04-29
 * Time: 16:56
 */

namespace App\Http\View;


use App\Cms\AbstractViewable;

class Header extends AbstractViewable
{
    protected $view = "web.header";

    protected $css = ["css/web/header.css"];

    protected $is_leaf = true;

    protected $title = "";

    protected $datetime;
    protected $week;
    public function __construct($title)
    {
        $this->title=$title;
        $this->datetime = date("Y年m月d日 H:i:s");

        $weekly = [
            0 => "星期日",
            1 => "星期一",
            2 => "星期二",
            3 => "星期三",
            4 => "星期四",
            5 => "星期五",
            6 => "星期六",
        ];
        $w = date("w");
        $this->week = $weekly[$w];

    }

    public function render()
    {
        return view($this->view, ["title" => $this->title, "datetime" => $this->datetime, "week" => $this->week]);
    }

    public function script(){
        $url = url("/chapter_list");
        return <<<SCRIPT
    $("#search_button").on("click",function () {
       let self = this;

       let keyword = $("#search").val();
       location.href = "{$url}?keyword=" + keyword;
    });
SCRIPT;

    }
}
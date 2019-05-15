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

        $this->addScript($this->script());

    }

    public function render()
    {
        $keyword = request()->get("keyword","");
        return view($this->view, ["title" => $this->title, "datetime" => $this->datetime, "week" => $this->week, "keyword" => $keyword]);
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

    public function script2(){
        return <<<SCRIPT
    function addFavorite() {
        var url = window.location;
        var title = document.title;
        var ua = navigator.userAgent.toLowerCase();
        if (ua.indexOf("msie 8") > -1) {
            external.AddToFavoritesBar(url, title, '');//IE8
        } else {
            try {
                window.external.addFavorite(url, title);
            } catch (e) {
                try {
                    window.sidebar.addPanel(title, url, "");//firefox
                } catch (e) {
                    alert("加入收藏失败，请使用Ctrl+D进行添加");
                }
            }
        }
    }


    function setHome(obj,vrl){
        try{
            obj.style.behavior='url(#default#homepage)';
            obj.setHomePage(vrl);
        }
        catch(e){
            if(window.netscape) {
                try {
                    netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                }
                catch (e) {
                    alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
                }
                var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
                prefs.setCharPref('browser.startup.homepage',vrl);
            }
        }
    }

SCRIPT;

    }
}
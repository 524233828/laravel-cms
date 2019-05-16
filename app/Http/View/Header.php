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
        $this->addScript($this->script2());
        $this->addScript($this->clock());

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
                    alert("此操作被浏览器拒绝！请在浏览器地址栏输入“about:config”并回车然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
                }
                var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
                prefs.setCharPref('browser.startup.homepage',vrl);
            }
        }
    }

SCRIPT;

    }

    public function clock()
    {
        return <<<SCRIPT
        
Date.prototype.Format = function(formatStr)   
{   
    var str = formatStr;   
    var Week = ['日','一','二','三','四','五','六'];  
   
    str=str.replace(/yyyy|YYYY/,this.getFullYear());   
    str=str.replace(/yy|YY/,(this.getYear() % 100)>9?(this.getYear() % 100).toString():'0' + (this.getYear() % 100));   
   
    let month = this.getMonth()+1;
    str=str.replace(/MM/,month>9?month.toString():'0' + month);   
    str=str.replace(/M/g,this.getMonth());   
   
    str=str.replace(/w|W/g,Week[this.getDay()]);   
   
    str=str.replace(/dd|DD/,this.getDate()>9?this.getDate().toString():'0' + this.getDate());   
    str=str.replace(/d|D/g,this.getDate());   
   
    str=str.replace(/hh|HH/,this.getHours()>9?this.getHours().toString():'0' + this.getHours());   
    str=str.replace(/h|H/g,this.getHours());   
    str=str.replace(/mm/,this.getMinutes()>9?this.getMinutes().toString():'0' + this.getMinutes());   
    str=str.replace(/m/g,this.getMinutes());   
   
    str=str.replace(/ss|SS/,this.getSeconds()>9?this.getSeconds().toString():'0' + this.getSeconds());   
    str=str.replace(/s|S/g,this.getSeconds());   
   
    return str;   
}   

setInterval(function(){
    var myDate = new Date();
    let time = myDate.Format("YYYY年MM月DD日 HH:mm:ss")
    $("#date_time").text(time);
},1000);

SCRIPT;

    }
}
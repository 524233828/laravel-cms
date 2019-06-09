<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-05-15
 * Time: 17:22
 */

namespace App\Http\View;


use App\Cms\AbstractViewable;

class Shenbao extends AbstractViewable
{
    protected $view = "web.shenbao";

    protected $css = ["css/web/shenbao.css",];

    protected $is_leaf = true;

    public function __construct()
    {
        $this->addScript($this->script());
    }

    public function render()
    {
        return view($this->view);
    }

    protected function script(){
        return <<<SCRIPT
        $(document).ready(function(){
            let left = (parseInt($(".container .content").css("padding-left"))-200)/2;
            let height = $("#shenbao2").outerHeight() - 100;
            console.log(height);
//            $(".qrcode-contain").css("height",height);
            
            let title_height = $(".qrcode-title").outerHeight();
            let qrcode_img = $(".qrcode-img").outerHeight();
            let qrcode_padding = (height-title_height-qrcode_img)/2;
//            $(".qrcode-contain").css("padding", qrcode_padding+"px 0");
//            $(".qrcode-contain").css("height", (height-qrcode_padding*2) + "px" );
            if(left<0){
                $("#shenbao").hide();
                $("#shenbao2").hide();
            }else{
                $("#shenbao").css("left", left);
                $("#shenbao").css("top",$(".container .content").offset().top + 10)
                $("#shenbao2").css("right", left);
                $("#shenbao2").css("top",$(".container .content").offset().top + 10)
                $(window).scroll(function (){
                    var topScroll=getScroll();
                    var topDiv="100px";
                    var top=topScroll+parseInt(topDiv);
                    var mintop = $(".container .content").offset().top + 10;
                    var maxtop = $(".container .footer").offset().top - $("#shenbao2").height() -10;
                    if(top<mintop){
                        top=mintop;
                    }
                    if(top>maxtop){
                        top = maxtop;
                    }
            
                    $("#shenbao").stop(true).animate({"top":top},50);
                    $("#shenbao2").stop(true).animate({"top":top},50);
                    
                })
                
                
            }
            function getScroll(){
                var bodyTop = 0;
                if (typeof window.pageYOffset != 'undefined') {
                    bodyTop = window.pageYOffset;
                } else if (typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat') {
                    bodyTop = document.documentElement.scrollTop;
                }
                else if (typeof document.body != 'undefined') {
                    bodyTop = document.body.scrollTop;
                }
                return bodyTop
            }
        });
SCRIPT;

    }
}
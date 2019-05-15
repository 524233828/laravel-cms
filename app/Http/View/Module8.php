<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-05-08
 * Time: 18:06
 */

namespace App\Http\View;


use App\Cms\AbstractViewable;
use App\Models\CmsImage;

class Module8 extends AbstractViewable
{

    protected $view = "web.module-8";

    protected $css = ["css/web/module_8.css",];

    protected $is_leaf = true;

    public function __construct()
    {
        $this->addScript($this->script());
    }

    protected function getImage()
    {
        $images = CmsImage::where([["status","=","1"],["type","=", "6"]])->orderBy("created_at","DESC")->limit(2)->get()->all();

        if(empty($images)){
            $images = [
            ];
        }
        return $images;
    }

    public function render()
    {
        return view($this->view, ["images" => $this->getImage()]);
        // TODO: Implement render() method.
    }

    protected function script(){
        return <<<SCRIPT
    let company_group_width = $(".module-8 .content").width() * 0.9668;

    let company_sum_width = company_group_width - 294;

    let company_width = parseInt(company_sum_width/7);

    let button_padding = (company_width-24)/2;

    if(button_padding < 0){
        button_padding = 0;
    }

    $(".company").width(company_width);

    $(".left-button").css("padding",button_padding + "px 0");
    $(".right-button").css("padding",button_padding + "px 0");

    jQuery(".module-8 .content").slide( { titCell:".hd ul",mainCell:".company-group",effect:"leftLoop",autoPlay:false,scroll:1,vis:7,easing:"swing",pnLoop:true,trigger:"click",mouseOverStop:true });
SCRIPT;

    }
}
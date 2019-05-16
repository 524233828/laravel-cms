<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-05-11
 * Time: 16:59
 */

namespace App\Http\View;


use App\Cms\AbstractViewable;
use App\Models\CmsFriendLink;

class Footer extends AbstractViewable
{

    protected $view = "web.footer";

    protected $css = ["css/web/footer.css",];

    protected $is_leaf = true;

    public function __construct()
    {
        $this->addScript($this->script());
    }

    protected function getFriend($type = 0)
    {
        $link = CmsFriendLink::where([["status","=","1"],["type","=", $type]])
            ->orderBy("created_at","DESC")->limit(8)->get()->all();

        return $link;
    }

    public function render()
    {

        return view($this->view, [
            "link0" => $this->getFriend(),
            "link1" => $this->getFriend(1)
        ]);
    }

    private function script()
    {
        return <<<SCRIPT
        $("#links1").hide();
$("#link-title-0").on("mouseover", function(){
    $("#links0").show();
    $("#links1").hide();
});

$("#link-title-1").on("mouseover", function(){
    $("#links1").show();
    $("#links0").hide();
});

$("#link-title-0").on("click", function(){
    $("#links0").show();
    $("#links1").hide();
});

$("#link-title-1").on("click", function(){
    $("#links1").show();
    $("#links0").hide();
});
SCRIPT;

    }
}
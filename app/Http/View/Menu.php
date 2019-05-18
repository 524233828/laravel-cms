<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-04-28
 * Time: 18:33
 */

namespace App\Http\View;


 use App\Cms\AbstractViewable;

class Menu extends AbstractViewable
{

    /**
     * @var \App\Models\CmsMenu;
     */
    protected $model;

    protected $view = "web.menu";

    protected $css = ["css/web/menu.css"];

    protected $is_leaf = true;

    public function __construct($model)
    {
        $this->model = $model;
        $this->addScript($this->script());
    }

    protected function getData()
    {
        return $this->model::where(["status"=>1])->orderByDesc("sort")->get()->all();
    }

    public function render()
    {
        // TODO: Implement render() method.
        return view($this->view, [
            "menus" => $this->getData()
        ]);
    }

    public function script(){
        return <<<SCRIPT
$(".menu-button").on("click", function(){
    if($(".menu").offset().left >= 0){
        $(".menu").animate({left:"-30%"});
    }else{
        $(".menu").animate({left:0});
    }
    
});

SCRIPT;

    }
}
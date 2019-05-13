<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-05-11
 * Time: 23:31
 */

namespace App\Http\View;


use App\Cms\AbstractViewable;

class BreadCrumb extends AbstractViewable
{
    protected $view = "web.bread-crumb";

    protected $css = ["css/web/bread-crumb.css",];

    protected $is_leaf = true;

    public $menu;

    public function __construct($menu)
    {
        $this->menu = $menu;
    }

    public function render()
    {
        return view($this->view, ["menu" => $this->menu]);
    }

}
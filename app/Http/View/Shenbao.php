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


    public function render()
    {
        return view($this->view);
    }
}
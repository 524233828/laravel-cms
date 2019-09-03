<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/1/25
 * Time: 22:22
 */

namespace App\Admin\Extensions\Actions;


use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Route;

class PreView implements Renderable
{
    protected $resource;
    protected $key;

    public function __construct($resource, $key)
    {
        $this->resource = $resource;
        $this->key = $key;
    }

    public function render()
    {
        $uri = url("/detail?id={$this->key}");

        return <<<EOT
<a href="{$uri}" title="预览" target="_blank">
    <i class="fa fa-eye"></i>
</a>
EOT;
    }

    public function __toString()
    {
        return $this->render();
    }
}
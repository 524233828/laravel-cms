<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-05-08
 * Time: 18:06
 */

namespace App\Http\View;


use App\Cms\AbstractViewable;
use App\Models\CmsChapter;
use App\Models\CmsChapterType;
use App\Models\CmsFile;
use function GuzzleHttp\Psr7\parse_query;

class Module13 extends AbstractViewable
{

    protected $view = "web.module-13";

    protected $css = ["css/web/module_13.css"];

    protected $is_leaf = true;

    protected $img_path;

    public function __construct($img_path)
    {
        $this->img_path = $img_path;
    }

    public function render()
    {

        return view($this->view, [
            "img_path" => $this->img_path
        ]);
    }
}
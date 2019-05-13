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
}
<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-03-26
 * Time: 12:00
 */

namespace App\Admin\Extensions\Tools;


use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class FilterDate extends AbstractTool
{

    public function render()
    {

        $start_time = Request::get("start_time", date("Y-m-d", strtotime("yesterday")));
        $end_time = Request::get("end_time", date("Y-m-d", strtotime($start_time)));

        return <<<EOT
        数据日期：{$start_time}~{$end_time}
EOT;
    }
}
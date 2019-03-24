<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-03-23 18:13:30
 */

namespace App\Admin\Controllers;

use App\Models\FcForecast;
use App\Models\FcForecastChannel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class FcForecastChannelController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('渠道价格管理');
            //小标题
            $content->description('渠道价格列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '渠道价格管理', 'url' => '/fc_forecast_channel']
            );

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('渠道价格管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '渠道价格管理', 'url' => '/fc_forecast_channel'],
                ['text' => '编辑']
            );

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('渠道价格管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '渠道价格管理', 'url' => '/fc_forecast_channel'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(FcForecastChannel::class, function (Grid $grid) {

            $grid->column("id","ID")->sortable();
            $grid->column("forecast.forecast_name","测算")->sortable();
            $grid->column("channel","渠道标识");
            $grid->column("channels.channel_name","渠道名称");
            $grid->column("amount","渠道价格")->sortable();
            $grid->column("status","状态")->using([0=>'冻结',1=>'启用']);
            $grid->column("create_time","创建时间")->display(function ($value){
                return date("Y-m-d H:i:s", $value);
            })->sortable();
            $grid->column("update_time","更新时间戳")->display(function ($value){
                return date("Y-m-d H:i:s", $value);
            })->sortable();


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","ID");
                $filter->equal("forecast_id","测算")->select($this->getForecast());
                $filter->where(function ($query) {
                    $query->where('channel', 'like', "{$this->input}%");
                }, '渠道标识');
                $filter->equal("status","状态")->select([0=>'冻结',1=>'启用']);

            });


        });
    }

    protected function form()
    {
        return Admin::form(FcForecastChannel::class, function (Form $form) {

            $form->display('id',"自增ID");
            $form->text('forecast_id',"测算ID")->rules("required|integer");
            $form->text('channel',"渠道标识")->rules("required|string");
            $form->text('amount',"渠道价格")->rules("required");
            $form->select("status","状态")->options([0=>'冻结',1=>'启用']);

//            $form->text('create_time',"创建时间")->rules("required|integer");
//            $form->text('update_time',"更新时间戳")->rules("required|integer");


        });
    }

    protected function getForecast()
    {
        $data = FcForecast::all();

        $data_options = [];
        foreach($data as $value)
        {
            $data_options[$value['id']] = $value['forecast_name'];
        }

        return $data_options;
    }
}
<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-03-23 18:05:54
 */

namespace App\Admin\Controllers;

use App\Models\FcForecast;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class FcForecastController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('测算管理');
            //小标题
            $content->description('测算列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '测算管理', 'url' => '/fc_forecast']
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

            $content->header('测算管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '测算管理', 'url' => '/fc_forecast'],
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

            $content->header('测算管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '测算管理', 'url' => '/fc_forecast'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(FcForecast::class, function (Grid $grid) {

            $grid->column("id","自增ID")->sortable();
            $grid->column("forecast_name","测算名字");
            $grid->column("default_amount","默认价格")->sortable();
            $grid->column("status","状态")->using([0=>'冻结',1=>'启用']);
            $grid->column("create_time","创建时间戳")->display(function ($value){
                return date("Y-m-d H:i:s", $value);
            })->sortable();
            $grid->column("update_time","更新时间戳")->display(function ($value){
                return date("Y-m-d H:i:s", $value);
            })->sortable();


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","自增ID");


            });


        });
    }

    protected function form()
    {
        return Admin::form(FcForecast::class, function (Form $form) {

            $form->display('id',"自增ID");
            $form->text('forecast_name',"测算名字")->rules("required|string");
            $form->text('view_uri',"视图层地址")->rules("required|string");
            $form->text('template',"入参过滤器")->rules("required|string");
            $form->text('default_amount',"默认价格")->rules("required");
            $form->select("status","状态")->options([0=>'冻结',1=>'启用']);

//            $form->text('create_time',"创建时间戳")->rules("required|integer");
//            $form->text('update_time',"更新时间戳")->rules("required|integer");
            $form->text('result_com',"结果来源接口")->rules("required|string");


        });
    }
}
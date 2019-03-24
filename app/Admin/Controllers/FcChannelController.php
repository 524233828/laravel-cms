<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-03-23 17:11:31
 */

namespace App\Admin\Controllers;

use App\Models\FcChannel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class FcChannelController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('渠道管理');
            //小标题
            $content->description('渠道列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '渠道管理', 'url' => '/fc_channel']
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

            $content->header('渠道管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '渠道管理', 'url' => '/fc_channel'],
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

            $content->header('渠道管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '渠道管理', 'url' => '/fc_channel'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(FcChannel::class, function (Grid $grid) {

            $grid->column("id","渠道号")->sortable();
            $grid->column("channel","channel");
            $grid->column("parent.channel_name","父渠道");
            $grid->column("channel_name","渠道名称");
            $grid->column("create_time","create_time")->display(function ($value){
                return date("Y-m-d H:i:s", $value);
            })->sortable();
            $grid->column("update_time","update_time")->display(function ($value){
                return date("Y-m-d H:i:s", $value);
            })->sortable();
            $grid->column("status","状态 0-冻结 1-可用")->using([0=>'冻结',1=>'启用']);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","渠道号");
                $filter->where(function ($query) {
                    $query->where('channel', 'like', "{$this->input}%");
                }, 'channel');
                $filter->equal("parent_id","parent_id");
                $filter->equal("status","状态 0-冻结 1-可用")->select([0=>'冻结',1=>'启用']);

            });


        });
    }

    protected function form()
    {
        return Admin::form(FcChannel::class, function (Form $form) {

            $form->display('id',"渠道号");
            $form->text('channel',"channel")->rules("required|string");
            $form->text('parent_id',"parent_id")->rules("required|integer");
            $form->text('channel_name',"渠道名称")->rules("required|string");
//            $form->text('create_time',"create_time")->rules("required|integer");
//            $form->text('update_time',"update_time")->rules("required|integer");
            $form->select("status","状态 0-冻结 1-可用")->options([0=>'冻结',1=>'启用']);



        });
    }
}
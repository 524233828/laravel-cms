<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2018-12-26 18:35:45
 */

namespace App\Admin\Controllers;

use App\Admin\Models\AdminDashboard;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class AdminDashboardController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('后台面板管理');
            //小标题
            $content->description('面板列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '后台面板管理', 'url' => '/admin_dashboard']
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

            $content->header('后台面板管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '后台面板管理', 'url' => '/admin_dashboard'],
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

            $content->header('后台面板管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '后台面板管理', 'url' => '/admin_dashboard'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(AdminDashboard::class, function (Grid $grid) {

            $grid->column("id","ID")->sortable();
            $grid->column("table_name","表名");
            $grid->column("header","面板名称");
            $grid->column("description","面板描述");
            $grid->column("route_tag","路由标签");
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","最近更新")->sortable();
            $grid->column("status","状态")->using([0=>"冻结",1=>"启用"]);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","ID");
                $filter->where(function ($query) {
                    $query->where('table_name', 'like', "{$this->input}%");
                }, '表名');
                $filter->equal("status","状态")->select([0=>"冻结",1=>"启用"]);



            });


        });
    }

    protected function form()
    {
        return Admin::form(AdminDashboard::class, function (Form $form) {

            $form->display('id',"ID");
            $form->text('table_name',"表名")->rules("required|string");
            $form->text('header',"面板名称")->rules("required|string");
            $form->text('description',"面板描述")->rules("required|string");
            $form->text('route_tag',"路由标签")->rules("required|string");
            $form->text('model',"模型")->rules("required|string");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"最近更新");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"]);



        });
    }
}
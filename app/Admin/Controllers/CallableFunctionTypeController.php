<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2018-12-16 11:25:30
 */

namespace App\Admin\Controllers;

use App\Models\CallableFunctionType;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class CallableFunctionTypeController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('可用方法类型');
            //小标题
            $content->description('类型列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '可用方法类型', 'url' => '/callable_function_type']
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

            $content->header('可用方法类型');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '可用方法类型', 'url' => '/callable_function_type'],
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

            $content->header('可用方法类型');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '可用方法类型', 'url' => '/callable_function_type'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(CallableFunctionType::class, function (Grid $grid) {

            $grid->column("id","id")->sortable();
            $grid->column("name","类型名称");
            $grid->column("status","状态")->using([0=>"冻结",1=>"启用"]);
            $grid->column("created_at", "创建时间")->sortable();
            $grid->column("updated_at", "最近修改时间")->sortable();


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","id");
                $filter->where(function ($query) {
                    $query->where('name', 'like', "{$this->input}%");
                }, '类型名称');
                $filter->equal("status","状态")->select([0=>"冻结",1=>"启用"]);
                $filter->between("created_at", "创建时间")->datetime();
                $filter->between("updated_at", "最近更新时间")->datetime();


            });


        });
    }

    protected function form()
    {
        return Admin::form(CallableFunctionType::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('name',"类型名称")->rules("required|string");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"]);


        });
    }
}
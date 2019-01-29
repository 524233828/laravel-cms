<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2018-12-16 11:42:52
 */

namespace App\Admin\Controllers;

use App\Models\CallableFunction;
use App\Http\Controllers\Controller;
use App\Models\CallableFunctionType;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class CallableFunctionController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('可用方法');
            //小标题
            $content->description('方法列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '可用方法', 'url' => '/callable_function']
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

            $content->header('可用方法');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '可用方法', 'url' => '/callable_function'],
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

            $content->header('可用方法');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '可用方法', 'url' => '/callable_function'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(CallableFunction::class, function (Grid $grid) {

            $grid->column("id","id")->sortable();
            $grid->column("type","函数类型")
                ->using($this->getFunctionType());
            $grid->column("function_name","函数名");
            $grid->column("class_name","类名");
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","最近更新时间")->sortable();
            $grid->column("status","状态")->using([0=>'冻结',1=>'启用']);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","id");
                $filter->where(function ($query) {
                    $query->where('function_name', 'like', "{$this->input}%");
                }, '函数名');
                $filter->where(function ($query) {
                    $query->where('class_name', 'like', "{$this->input}%");
                }, '类名');
                $filter->between("created_at","创建时间")->datetime();


            });


        });
    }

    protected function form()
    {
        return Admin::form(CallableFunction::class, function (Form $form) {



            $form->display('id',"id");
            $form->select("type","函数类型")
                ->options($this->getFunctionType());

            $form->text('function_name',"函数名")->rules("required|string");
            $form->text('class_name',"类名");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"最近更新时间");
            $form->select("status","状态")->options([0=>'冻结',1=>'启用']);



        });
    }

    protected function getFunctionType()
    {
        $type = CallableFunctionType::all();

        $type_options = [];
        foreach($type as $value)
        {
            $type_options[$value['id']] = $value['name'];
        }

        return $type_options;
    }
}
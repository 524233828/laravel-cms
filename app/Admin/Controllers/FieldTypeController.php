<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2018-12-23 22:07:02
 */

namespace App\Admin\Controllers;

use App\Admin\Models\AdminFieldType;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class FieldTypeController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('字段类型');
            //小标题
            $content->description('字段类型列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '字段类型', 'url' => '/field_type']
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

            $content->header('字段类型');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '字段类型', 'url' => '/field_type'],
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

            $content->header('字段类型');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '字段类型', 'url' => '/field_type'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(AdminFieldType::class, function (Grid $grid) {

            $grid->column("id","ID")->sortable();
            $grid->column("name","名称");
            $grid->column("show_type","展示类型");
            $grid->column("field_type","字段类型");
            $grid->column("validator","字段校验器");
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","最近更新")->sortable();
            $grid->column("status","状态")->using([0=>"冻结",1=>"启用"]);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","ID");
                $filter->where(function ($query) {
                    $query->where('show_type', 'like', "{$this->input}%");
                }, '展示类型');
                $filter->where(function ($query) {
                    $query->where('field_type', 'like', "{$this->input}%");
                }, '字段类型');
                $filter->equal("status","状态")->select([0=>"冻结",1=>"启用"]);



            });


        });
    }

    protected function form()
    {
        return Admin::form(AdminFieldType::class, function (Form $form) {

            $form->display('id',"ID");
            $form->text('name',"名称")->rules("required|string");
            $form->text('show_type',"展示类型")->rules("required|string");
            $form->text('field_type',"字段类型")->rules("required|string");
            $form->text('show_id',"展示形式")->rules("required|string");
            $form->text('filter_id',"过滤形式")->rules("required|string");
            $form->text('edit_id',"编辑形式")->rules("required|string");
            $form->text('validator',"字段校验器")->rules("required|string");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"最近更新");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"]);



        });
    }
}
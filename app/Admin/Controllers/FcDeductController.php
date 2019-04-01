<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-03-30 09:35:52
 */

namespace App\Admin\Controllers;

use App\Models\FcDeduct;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class FcDeductController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('扣量管理');
            //小标题
            $content->description('扣量列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '扣量管理', 'url' => '/fc_deduct']
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

            $content->header('扣量管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '扣量管理', 'url' => '/fc_deduct'],
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

            $content->header('扣量管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '扣量管理', 'url' => '/fc_deduct'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(FcDeduct::class, function (Grid $grid) {

            $grid->column("id","id")->sortable();
            $grid->column("channel","渠道")->sortable();
            $grid->column("order_num","每n单扣一单量")->sortable();
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","更新时间")->sortable();
            $grid->column("status","状态")->using([0=>"冻结",1=>"启用"])->sortable();


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","id");
                $filter->where(function ($query) {
                    $query->where('channel', 'like', "{$this->input}%");
                }, '渠道');
                $filter->equal("status","状态")->select([0=>"冻结",1=>"启用"]);



            });


        });
    }

    protected function form()
    {
        return Admin::form(FcDeduct::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('channel',"渠道")->rules("required|string");
            $form->text('order_num',"每n单扣一单量")->rules("required|integer");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"更新时间");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"])->default(1);



        });
    }
}
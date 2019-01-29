<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2018-12-07 11:55:19
 */

namespace App\Admin\Controllers;

use App\Models\ChapterModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class ChapterController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('文章管理');
            //小标题
            $content->description('文章列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '文章管理', 'url' => '/chapter']
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

            $content->header('文章管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '文章管理', 'url' => '/chapter'],
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

            $content->header('文章管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '文章管理', 'url' => '/chapter'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(ChapterModel::class, function (Grid $grid) {

            $grid->column("id","id")->sortable();
            $grid->column("title","标题");
            $grid->column("status","状态 0-冻结 1-可用")->using([0=>"启动",1=>"冻结"]);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","id");
                $filter->where(function ($query) {
                    $query->where('title', 'like', "{$this->input}%");
                }, '标题');
                $filter->equal("status","状态 0-冻结 1-可用")->select([0=>"启动",1=>"冻结"]);



            });


        });
    }

    protected function form()
    {
        return Admin::form(ChapterModel::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('title',"标题")->rules("required|string");
            $form->editor('content', 'content')->rules("required|string");
            $form->select("status","状态 0-冻结 1-可用")->options([0=>"启动",1=>"冻结"]);



        });
    }
}
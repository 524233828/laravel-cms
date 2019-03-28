<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-03-27 21:04:40
 */

namespace App\Admin\Controllers;

use App\Models\BdtjPage;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class BdtjPageController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('页面管理');
            //小标题
            $content->description('页面列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '页面管理', 'url' => '/bdtj_pages']
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

            $content->header('页面管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '页面管理', 'url' => '/bdtj_pages'],
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

            $content->header('页面管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '页面管理', 'url' => '/bdtj_pages'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(BdtjPage::class, function (Grid $grid) {

            $grid->column("page_id","页面ID")->sortable();
            $grid->column("name","页面链接")->limit(45);
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","更新时间")->sortable();
            $grid->column("site_id","站点")->sortable();
            $grid->column("status","状态")->using([0=>"冻结",1=>"启用"]);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->where(function ($query) {
                    $query->where('page_id', 'like', "{$this->input}%");
                }, '页面ID');
                $filter->equal("site_id","站点");
                $filter->equal("status","状态")->select([0=>"冻结",1=>"启用"]);



            });


        });
    }

    protected function form()
    {
        return Admin::form(BdtjPage::class, function (Form $form) {

            $form->display('page_id',"页面ID");
            $form->text('name',"页面链接")->rules("required|string");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"更新时间");
            $form->text('site_id',"站点")->rules("required|integer");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"]);



        });
    }
}
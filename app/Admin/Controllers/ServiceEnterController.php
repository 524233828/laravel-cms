<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-05-11 15:44:21
 */

namespace App\Admin\Controllers;

use App\Models\CmsServiceEnter;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class ServiceEnterController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('快速服务入口');
            //小标题
            $content->description('服务列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '快速服务入口', 'url' => '/service_enters']
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

            $content->header('快速服务入口');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '快速服务入口', 'url' => '/service_enters'],
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

            $content->header('快速服务入口');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '快速服务入口', 'url' => '/service_enters'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(CmsServiceEnter::class, function (Grid $grid) {

            $grid->column("id","ID");
            $grid->column("images","图片")->display(function ($value){
                return "<img src='/{$value}'>";
            });
            $grid->column("name","名字");
            $grid->column("created_at","创建时间");

            $grid->disableCreateButton();

            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){



            });


        });
    }

    protected function form()
    {
        return Admin::form(CmsServiceEnter::class, function (Form $form) {

            $form->display('id',"ID");
//            $form->text('images',"图片")->rules("required|string");
            $form->text('name',"名字")->rules("required|string");
            $form->text('url',"链接")->rules("required|string");
//            $form->datetime('created_at',"创建时间");
//            $form->datetime('updated_at',"更新时间");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"]);



        });
    }
}
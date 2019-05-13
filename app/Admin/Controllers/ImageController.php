<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-05-11 09:48:28
 */

namespace App\Admin\Controllers;

use App\Models\CmsImage;
use App\Http\Controllers\Controller;
use App\Models\CmsImageType;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class ImageController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('图片管理');
            //小标题
            $content->description('图片列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '图片管理', 'url' => '/images']
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

            $content->header('图片管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '图片管理', 'url' => '/images'],
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

            $content->header('图片管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '图片管理', 'url' => '/images'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(CmsImage::class, function (Grid $grid) {

            $grid->column("id","ID")->sortable();
            $grid->column("name","图片描述");
            $grid->column("types.name","图片类型")->sortable();
            $grid->column("path","图片位置");
            $grid->column("status","状态")->using([0=>"冻结", 1=>"启用"]);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("type","图片类型");


            });


        });
    }

    protected function form()
    {
        return Admin::form(CmsImage::class, function (Form $form) {

            $form->display('id',"ID");
            $form->text('name',"图片描述")->rules("required|string");
            $form->select('type',"图片类型")->options(CmsImageType::getType())->rules("required|integer");
//            $form->text('path',"图片位置")->rules("required|string");
            $form->image('path', "图片上传");
            $form->text('url',"图片点击跳转链接")->rules("required|string");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"更新时间");
            $form->select("status","状态")->options([0=>"冻结", 1=>"启用"])->default(1);



        });
    }
}
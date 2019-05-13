<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-05-12 16:10:26
 */

namespace App\Admin\Controllers;

use App\Models\CmsFile;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class FilesController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('下载列表');
            //小标题
            $content->description('下载列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '下载列表', 'url' => '/files']
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

            $content->header('下载列表');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '下载列表', 'url' => '/files'],
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

            $content->header('下载列表');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '下载列表', 'url' => '/files'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(CmsFile::class, function (Grid $grid) {

            $grid->column("id","ID");
            $grid->column("name","文件名");
//            $grid->column("size","大小（B）")->sortable();
            $grid->column("created_at","创建时间")->sortable();


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){



            });


        });
    }

    protected function form()
    {
        return Admin::form(CmsFile::class, function (Form $form) {

            $form->display('id',"ID");
            $form->text('name',"文件名")->rules("required|string");
//            $form->text('path',"文件位置")->rules("required|string");
            $form->file('path', "上传文件");
//            $form->text('size',"大小（B）")->rules("required|integer");
//            $form->datetime('created_at',"创建时间");
//            $form->datetime('updated_at',"更新时间");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"])->default(1);



        });
    }
}
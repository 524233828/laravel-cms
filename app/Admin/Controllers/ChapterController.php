<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-05-11 09:46:07
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Actions\ChapterCheck;
use App\Models\CmsChapter;
use App\Http\Controllers\Controller;
use App\Models\CmsChapterType;
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
                ['text' => '文章管理', 'url' => '/chapters']
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
                ['text' => '文章管理', 'url' => '/chapters'],
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
                ['text' => '文章管理', 'url' => '/chapters'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(CmsChapter::class, function (Grid $grid) {

            $grid->column("id","ID")->sortable();
            $grid->column("title","文章标题");
            $grid->column("types.name","文章分类")->sortable();
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","更新时间");
            $grid->column("status","状态")->using([0=>"不通过审核",1=>"待审核",2=>"已审核",3=>"已发布"]);


            $grid->actions(function (Grid\Displayers\Actions $actions){
                $actions->append(new ChapterCheck($actions->getResource(), $actions->getKey()));
            });

            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->where(function ($query) {
                    $query->where('title', 'like', "{$this->input}%");
                }, '文章标题');
                $filter->equal("type","文章分类");


            });


        });
    }

    protected function form()
    {
        return Admin::form(CmsChapter::class, function (Form $form) {

            $form->display('id',"ID");
            $form->select('type',"文章分类")->options(CmsChapterType::getType())->rules("required");
            $form->text('title',"文章标题")->rules("required|string");
            $form->kindeditor('content', '文章内容')->rules("required|string");
//            $form->datetime('created_at',"创建时间");
//            $form->datetime('updated_at',"更新时间");
//            $form->select("status","状态")->options([0=>"冻结",1=>"待审核",2=>"已审核",3=>"已发布"]);

        });
    }
}
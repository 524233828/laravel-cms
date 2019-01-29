<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/8/6
 * Time: 15:41
 */

namespace App\Admin\Controllers;


use App\Admin\Models\Demo;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class DemoController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('模板页面');
            //小标题
            $content->description('模板页面');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/admin'],
                ['text' => 'Demo', 'url' => '/admin/demo']
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

            $content->header('模板页面');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/admin'],
                ['text' => 'Demo', 'url' => '/admin/demo'],
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

            $content->header('模板页面');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/admin'],
                ['text' => 'Demo', 'url' => '/admin/demo'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Demo::class, function (Grid $grid) {

            //主键
            $grid->column("id","ID")->sortable();

            //varchar字段
            $grid->column("title","标题");

            //text字段，text字段最好不显示在列表中
            $grid->column("content","内容")->display(function($content){

                return "<pre>{$content}</pre>";
            });

            //tinyint类型，需设置枚举值
            $grid->column("status","状态")
                ->using([ 0 => '冻结', 1=>'启用']);
//                ->display(function($status){
//                switch ($status){
//                    case 0 :
//                        return "冻结";
//                    case 1 :
//                        return "启用";
//                    default:
//                        return "未知";
//                }
//            });

            //datetime类型
            $grid->column("create_time","创建时间")->sortable();
            $grid->column("update_time","最后修改时间")->sortable();

            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

//                $filter->equal("title","标题");
                $filter->where(function ($query) {

                    $query->where('title', 'like', "{$this->input}%");

                }, '标题');

                //datetime字段
                $filter->between("create_time","创建时间")->datetime();

                //tinyint字段
                $filter->equal("status")->select([ 0 => '冻结', 1=>'启用']);

                /**
                 * 此段用于搜索引擎检索
                 */
//                $filter->where(function(Builder $query){
//
//                    //TODO: 调用搜索引擎
//
//
//                }, "搜索引擎搜索");
            });


        });
    }

    protected function form()
    {
        return Admin::form(Demo::class, function (Form $form) {

            //主键，禁止修改
            $form->display('id', 'ID');

            //varchar字段
            $form->text('title', '标题')->rules("required|string");

            //text字段
            $form->editor('content', '内容')->rules("required|string");

            //tinyint字段
            $form->select("status", "状态")->options([ 0 => '冻结', 1=>'启用']);

            //datetime字段
            $form->datetime('create_time', '创建时间');
            $form->datetime('update_time', '最后更新时间');

        });
    }
}
<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-02-01 22:01:15
 */

namespace App\Admin\Controllers;

use App\Models\WechatUserEventType;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class WechatUserEventTypeController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('事件类型');
            //小标题
            $content->description('事件类型列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '事件类型', 'url' => '/wechat_user_event_types']
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

            $content->header('事件类型');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '事件类型', 'url' => '/wechat_user_event_types'],
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

            $content->header('事件类型');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '事件类型', 'url' => '/wechat_user_event_types'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(WechatUserEventType::class, function (Grid $grid) {

            $grid->column("id","id");
            $grid->column("tag","类型标识");
            $grid->column("name","类型名称");
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","最近更新时间")->sortable();
            $grid->column("status","状态")->using([0=>"冻结",1=>"启用"]);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->where(function ($query) {
                    $query->where('tag', 'like', "{$this->input}%");
                }, '类型标识');
                $filter->equal("status","状态")->select([0=>"冻结",1=>"启用"]);



            });


        });
    }

    protected function form()
    {
        return Admin::form(WechatUserEventType::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('tag',"类型标识")->rules("required|string");
            $form->text('name',"类型名称")->rules("required|string");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"最近更新时间");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"])->default(1);



        });
    }
}
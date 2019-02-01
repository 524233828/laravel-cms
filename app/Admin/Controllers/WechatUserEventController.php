<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-02-01 20:14:29
 */

namespace App\Admin\Controllers;

use App\Models\WechatUserEvent;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class WechatUserEventController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('事件接收');
            //小标题
            $content->description('用户事件列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '事件接收', 'url' => '/wechat_user_events']
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

            $content->header('事件接收');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '事件接收', 'url' => '/wechat_user_events'],
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

            $content->header('事件接收');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '事件接收', 'url' => '/wechat_user_events'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(WechatUserEvent::class, function (Grid $grid) {

            $grid->column("msgid","消息ID");
            $grid->column("to_user_name","接收方ID");

            $grid->column("from_user_name","发送方ID");
            $grid->column("msg_type","消息类型");
            $grid->column("create_time","消息生成时间")->sortable();
            $grid->column("received_at","消息接收时间")->sortable();
            $grid->column("updated_at","最近更新时间")->sortable();
            $grid->column("status","状态")->using([0=>"冻结",1=>"启用"]);
            $grid->column("account.name","所属公众号");

            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->where(function ($query) {
                    $query->where('msgid', 'like', "{$this->input}%");
                }, '消息ID');
                $filter->where(function ($query) {
                    $query->where('to_user_name', 'like', "{$this->input}%");
                }, '接收方ID');
                $filter->where(function ($query) {
                    $query->where('from_user_name', 'like', "{$this->input}%");
                }, '发送方ID');
                $filter->where(function ($query) {
                    $query->where('msg_type', 'like', "{$this->input}%");
                }, '消息类型');
                $filter->equal("status","状态")->select([0=>"冻结",1=>"启用"]);



            });


        });
    }

    protected function form()
    {
        return Admin::form(WechatUserEvent::class, function (Form $form) {

            $form->display('msgid',"消息ID");
            $form->text('to_user_name',"接收方ID")->rules("required|string");
            $form->text('from_user_name',"发送方ID")->rules("required|string");
            $form->text('msg_type',"消息类型")->rules("required|string");
            $form->datetime('create_time',"消息生成时间");
            $form->datetime('received_at',"消息接收时间");
            $form->datetime('updated_at',"最近更新时间");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"]);

            $form->editor('body', '完整的消息体')->rules("required|string");


        });
    }
}
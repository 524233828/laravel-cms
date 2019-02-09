<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-02-08 22:32:02
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Actions\WechatReceivedEventReply;
use App\Models\WechatReceivedEvent;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class WechatReceivedEventController extends Controller
{

    use HasResourceActions;

    public function index($wx_app_id)
    {
        return Admin::content(function (Content $content) use($wx_app_id) {

            //页面描述
            $content->header('事件接收处理');
            //小标题
            $content->description('接收事件列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '事件接收处理', 'url' => '/wechat_received_events']
            );

            $content->body($this->grid($wx_app_id));
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($wx_app_id, $id)
    {
        return Admin::content(function (Content $content) use ($wx_app_id, $id) {

            $content->header('事件接收处理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '事件接收处理', 'url' => '/wechat_received_events'],
                ['text' => '编辑']
            );

            $content->body($this->form($wx_app_id)->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create($wx_app_id)
    {
        return Admin::content(function (Content $content) use($wx_app_id) {

            $content->header('事件接收处理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '事件接收处理', 'url' => '/wechat_received_events'],
                ['text' => '新增']
            );

            $content->body($this->form($wx_app_id));
        });
    }

    public function grid($wx_app_id)
    {
        return Admin::grid(WechatReceivedEvent::class, function (Grid $grid) use ($wx_app_id) {

            $grid->model()->where("wx_app_id", $wx_app_id);
            $grid->column("id","id");
            $grid->column("name","描述");
            $grid->column("event","事件标识");
            $grid->column("event_key","事件键");
            $grid->column("account.name","所属公众号");
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","最近更新时间")->sortable();
            $grid->column("status","状态")->using([0=>'冻结',1=>'启用']);

            $grid->actions(function(Grid\Displayers\Actions $actions){

                $actions->append(new WechatReceivedEventReply($actions->getResource(), $actions->getKey()));
            });

            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->where(function ($query) {
                    $query->where('event', 'like', "{$this->input}%");
                }, '事件标识');
                $filter->where(function ($query) {
                    $query->where('event_key', 'like', "{$this->input}%");
                }, '事件键');
                $filter->where(function ($query) {
                    $query->where('wx_app_id', 'like', "{$this->input}%");
                }, 'wx_app_id');


            });


        });
    }

    protected function form($wx_app_id)
    {
        return Admin::form(WechatReceivedEvent::class, function (Form $form) use($wx_app_id) {

            $form->display('id',"id");
            $form->hidden('wx_app_id',"所属公众号ID")->default($wx_app_id);
            $form->text('name',"描述")->rules("required|string");
            $form->text('event',"事件标识")->rules("required|string");
            $form->text('event_key',"事件键");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"最近更新时间");
            $form->select("status","状态")->options([0=>'冻结',1=>'启用'])->default(1);



        });
    }

    public function update($wx_app_id, $id)
    {
        return $this->form($wx_app_id)->update($id);
    }

    public function store($wx_app_id)
    {
        return $this->form($wx_app_id)->store();
    }
}
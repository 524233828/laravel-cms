<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-02-04 12:31:40
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Actions\WechatReceivedReply;
use App\Models\WechatReceivedText;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class WechatReceivedTextController extends Controller
{

    use HasResourceActions;

    public function index($wx_app_id)
    {
        return Admin::content(function (Content $content) use($wx_app_id) {

            //页面描述
            $content->header('需处理文本消息');
            //小标题
            $content->description('消息列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '需处理文本消息', 'url' => '/wechat_received_texts']
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

            $content->header('需处理文本消息');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '需处理文本消息', 'url' => '/wechat_received_texts'],
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

            $content->header('需处理文本消息');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '需处理文本消息', 'url' => '/wechat_received_texts'],
                ['text' => '新增']
            );

            $content->body($this->form($wx_app_id));
        });
    }

    public function grid($wx_app_id)
    {
        return Admin::grid(WechatReceivedText::class, function (Grid $grid) use($wx_app_id) {

            $grid->model()->where("wx_app_id", $wx_app_id);

            $grid->column("id","id")->sortable();
            $grid->column("name","描述");
            $grid->column("content","文本");
            $grid->column("type","类型")->using([0=>'半匹配',1=>'全匹配']);
            $grid->column("account.name","所属公众号");
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","最近更新时间")->sortable();
            $grid->column("status","状态")->using([0=>'冻结',1=>'启用']);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","id");
                $filter->equal("type","类型")->select([0=>'半匹配',1=>'全匹配']);

                $filter->equal("status","状态")->select([0=>'冻结',1=>'启用']);



            });

            $grid->actions(function(Grid\Displayers\Actions $actions){

                $actions->append(new WechatReceivedReply($actions->getResource(), $actions->getKey()));
            });


        });
    }

    protected function form($wx_app_id)
    {
        return Admin::form(WechatReceivedText::class, function (Form $form) use($wx_app_id) {


            $form->display('id',"id");
            $form->hidden('wx_app_id',"所属公众号ID")->default($wx_app_id);
            $form->text('name',"描述")->rules("required|string");
            $form->text('content',"文本")->rules("required|string");
            $form->select("type","类型")->options([0=>'半匹配',1=>'全匹配']);

            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"最近更新时间");
            $form->select("status","状态")->options([0=>'冻结',1=>'启用'])->default(1);



        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($wx_app_id, $id)
    {
        return $this->form($wx_app_id)->update($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store($wx_app_id)
    {
        return $this->form($wx_app_id)->store();
    }
}
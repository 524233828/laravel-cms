<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-02-04 12:34:35
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Form\Fields\Editors\WechatTextEditor;
use App\Models\WechatReplyText;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\Input;

class WechatReplyTextController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('文本回复消息');
            //小标题
            $content->description('消息列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '文本回复消息', 'url' => '/wechat_reply_texts']
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

            $content->header('文本回复消息');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '文本回复消息', 'url' => '/wechat_reply_texts'],
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

            $content->header('文本回复消息');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '文本回复消息', 'url' => '/wechat_reply_texts'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(WechatReplyText::class, function (Grid $grid) {

            $grid->column("id","id");
            $grid->column("name","描述");
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","最近更新时间")->sortable();
            $grid->column("status","状态")->using([0=>'冻结',1=>'启用']);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("status","状态")->select([0=>'冻结',1=>'启用']);



            });


        });
    }

    protected function form()
    {
        return Admin::form(WechatReplyText::class, function (Form $form) {

            Form::extend("wteditor", WechatTextEditor::class);
            $form->display('id',"id");
            $form->text('name',"描述")->rules("required|string");
            $form->wteditor('content', '内容')->rules("required|string");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"最近更新时间");
            $form->select("status","状态")->options([0=>'冻结',1=>'启用'])->default(1);

            $form->saving(function(Form $form)
            {
                $content = Input::get("content");

                $content = implode("\n", explode("<br />", $content));

                Input::merge(["content" => $content]);

            });

        });
    }
}
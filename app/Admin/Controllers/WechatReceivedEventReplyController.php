<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-02-08 23:05:52
 */

namespace App\Admin\Controllers;

use App\Models\WechatReceivedEventReply;
use App\Http\Controllers\Controller;
use App\Models\WechatUserEvent;
use App\Models\WechatUserEventType;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class WechatReceivedEventReplyController extends Controller
{

    use HasResourceActions;

    public function index($received_id)
    {
        return Admin::content(function (Content $content) use ($received_id) {

            //页面描述
            $content->header('事件响应');
            //小标题
            $content->description('事件响应列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '事件响应', 'url' => '/wechat_received_event_replies']
            );

            $content->body($this->grid($received_id));
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($received_id, $id)
    {
        return Admin::content(function (Content $content) use ($received_id, $id) {

            $content->header('事件响应');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '事件响应', 'url' => '/wechat_received_event_replies'],
                ['text' => '编辑']
            );

            $content->body($this->form($received_id)->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create($received_id)
    {
        return Admin::content(function (Content $content) use($received_id) {

            $content->header('事件响应');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '事件响应', 'url' => '/wechat_received_event_replies'],
                ['text' => '新增']
            );

            $content->body($this->form($received_id));
        });
    }

    public function grid($received_id)
    {
        return Admin::grid(WechatReceivedEventReply::class, function (Grid $grid) use ($received_id) {

            $grid->model()->where("received_id", $received_id);
            $grid->column("id","id");
            $grid->column("receiver.name","接收者");
            $grid->column("reply_id","回复者");
            $grid->column("types.name","回复类型");
            $grid->column("sort","排序")->sortable();
            $grid->column("created_at","created_at")->sortable();
            $grid->column("updated_at","updated_at")->sortable();


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("received_id","接收者");
                $filter->equal("reply_id","回复者");
                $filter->equal("type","回复类型");


            });


        });
    }

    protected function form($received_id)
    {
        return Admin::form(WechatReceivedEventReply::class, function (Form $form) use ($received_id) {

            $form->display('id',"id");
            $form->hidden('received_id',"接收者")->default($received_id);
            $form->select('type',"类型")->options($this->getType())->load("reply_id","/api/admin/reply");
            $form->select('reply_id',"回复者");
            $form->text('sort',"排序")->rules("required|integer");
            $form->datetime('created_at',"created_at");
            $form->datetime('updated_at',"updated_at");


        });
    }

    protected function getType()
    {
        $data = WechatUserEventType::all();

        $data_options = [];
        foreach($data as $value)
        {
            $data_options[$value['id']] = $value['name'];
        }

        return $data_options;
    }

    public function update($received_id, $id)
    {
        return $this->form($received_id)->update($id);
    }

    public function store($received_id)
    {
        return $this->form($received_id)->store();
    }
}
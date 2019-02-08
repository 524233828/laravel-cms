<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-02-04 12:49:31
 */

namespace App\Admin\Controllers;

use App\Models\WechatReceivedReply;
use App\Http\Controllers\Controller;
use App\Models\WechatUserEventType;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class WechatReceivedReplyController extends Controller
{

    use HasResourceActions;

    public function index($received_id)
    {
        return Admin::content(function (Content $content) use($received_id) {

            //页面描述
            $content->header('消息回复列表');
            //小标题
            $content->description('消息回复列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '消息回复列表', 'url' => '/wechat_received_replys']
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
    public function edit($received_id,$id)
    {
        return Admin::content(function (Content $content) use ($received_id,$id) {

            $content->header('消息回复列表');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '消息回复列表', 'url' => '/wechat_received_replys'],
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

            $content->header('消息回复列表');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '消息回复列表', 'url' => '/wechat_received_replys'],
                ['text' => '新增']
            );

            $content->body($this->form($received_id));
        });
    }

    public function grid($received_id)
    {
        return Admin::grid(WechatReceivedReply::class, function (Grid $grid) use ($received_id) {

            $grid->model()->where("received_id", $received_id);
            $grid->column("id","id");
            $grid->column("receiver.name","接收者");
            $grid->column("reply_id","回复者ID");
            $grid->column("types.name","类型");
            $grid->column("sort","排序权重")->sortable();
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","更新时间")->sortable();


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("received_id","接收者ID");
                $filter->equal("reply_id","回复者ID");
                $filter->equal("type","类型");


            });


        });
    }

    protected function form($received_id)
    {
        return Admin::form(WechatReceivedReply::class, function (Form $form) use($received_id) {

            $form->display('id',"id");
            $form->hidden('received_id',"接收者ID")->default($received_id);
            $form->select('type',"类型")->options($this->getType())->load("reply_id","/api/admin/reply");
            $form->select('reply_id',"回复者");
            $form->text('sort',"排序权重")->rules("required|integer");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"更新时间");


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

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($received_id, $id)
    {
        return $this->form($received_id)->update($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store($received_id)
    {
        return $this->form($received_id)->store();
    }
}
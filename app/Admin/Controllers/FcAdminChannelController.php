<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-03-28 10:29:06
 */

namespace App\Admin\Controllers;

use App\Models\FcAdminChannel;
use App\Http\Controllers\Controller;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;

class FcAdminChannelController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('渠道商渠道管理');
            //小标题
            $content->description('渠道商渠道列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '渠道商渠道管理', 'url' => '/fc_admin_channels']
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

            $content->header('渠道商渠道管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '渠道商渠道管理', 'url' => '/fc_admin_channels'],
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

            $content->header('渠道商渠道管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '渠道商渠道管理', 'url' => '/fc_admin_channels'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(FcAdminChannel::class, function (Grid $grid) {

            $grid->column("id","ID")->sortable();
            $grid->column("admin.name","用户")->sortable();
            $grid->column("channel","渠道");
            $grid->column("status","状态")->using([0=>"冻结",1=>"启用"]);
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","更新时间")->sortable();


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","ID");
                $filter->equal("admin_id","用户");
                $filter->where(function ($query) {
                    $query->where('channel', 'like', "{$this->input}%");
                }, '渠道');
                $filter->equal("status","状态")->select([0=>"冻结",1=>"启用"]);



            });


        });
    }

    protected function form()
    {
        return Admin::form(FcAdminChannel::class, function (Form $form) {

            $users = $this->getChannelUsers();
            $form->display('id',"ID");
            $form->select('admin_id',"用户")->options($users)->rules("required|integer");
            $form->text('channel',"渠道")->rules("required|string");
//            $form->select("status","状态")->options([0=>"冻结",1=>"启用"])->default(1);

            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"更新时间");


        });
    }

    public function getChannelUsers()
    {
        $role_id = 2;

        $roles = DB::table("admin_role_users")->where(["role_id" => $role_id])->get();

        $admin_ids = [];

        foreach ($roles as $role){
            $admin_ids[] = $role->user_id;
        }

        $admins = Administrator::whereKey($admin_ids)->get();

        $result = [];
        foreach ($admins as $admin)
        {
            $result[$admin->id] = $admin->name;
        }

        return $result;
    }
}
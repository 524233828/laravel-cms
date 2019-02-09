<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-01-18 10:20:39
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Actions\WechatMenu;
use App\Admin\Extensions\Actions\WechatReceivedEvent;
use App\Admin\Extensions\Actions\WechatReceivedText;
use App\Models\WechatOfficialAccount;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class WechatOfficialAccountController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('公众号管理');
            //小标题
            $content->description('公众号列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '公众号管理', 'url' => '/wechat_official_accounts']
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

            $content->header('公众号管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '公众号管理', 'url' => '/wechat_official_accounts'],
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

            $content->header('公众号管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '公众号管理', 'url' => '/wechat_official_accounts'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(WechatOfficialAccount::class, function (Grid $grid) {

            $grid->column("name","公众号");
            $grid->column("wx_app_id","公众号ID");
            $grid->column("original_id","公众号原始ID");
            $grid->column("apps.name","所属应用");
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","最近更新时间")->sortable();
            $grid->column("status","状态")->using([0=>"冻结", 1=>"启用"]);

            $grid->actions(function(Grid\Displayers\Actions $actions){

                $actions->append(new WechatMenu($actions->getResource(), $actions->getKey()));
                $actions->append(new WechatReceivedText($actions->getResource(), $actions->getKey()));
                $actions->append(new WechatReceivedEvent($actions->getResource(), $actions->getKey()));
            });


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->where(function ($query) {
                    $query->where('wx_app_id', 'like', "{$this->input}%");
                }, '公众号ID');
                $filter->equal("status","状态")->select([0=>"冻结", 1=>"启用"]);



            });


        });
    }

    protected function form()
    {
        return Admin::form(WechatOfficialAccount::class, function (Form $form) {

            $form->text('name',"公众号")->rules("required|string");
            $form->text('wx_app_id',"公众号ID")->rules("required|string");
            $form->text('app_secret',"公众号密钥")->rules("required|string");
            $form->text('token',"公众号token")->rules("required|string");
            $form->text('original_id',"公众号原始ID")->rules("required|string");
            $form->text('aes_key',"消息加解密密钥")->rules("string");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"最近更新时间");
            $form->select("status","状态")->options([0=>"冻结", 1=>"启用"]);



        });
    }
}
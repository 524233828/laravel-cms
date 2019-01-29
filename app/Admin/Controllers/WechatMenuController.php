<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-01-20 12:07:09
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Actions\WechatMenuConfig;
use App\Admin\Extensions\Tools\WechatMenusCreate;
use App\Admin\Extensions\Tools\WechatMenusReturn;
use App\Models\WechatMenu;
use App\Http\Controllers\Controller;
use App\Models\WechatMenuType;
use App\Models\WechatOfficialAccount;
use App\Services\WechatOfficial\WechatOfficialService;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class WechatMenuController extends Controller
{

    use HasResourceActions;

    public function index($wx_app_id)
    {
        return Admin::content(function (Content $content) use ($wx_app_id){

            //页面描述
            $content->header('微信自定义菜单');
            //小标题
            $content->description('自定义菜单列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '公众号管理', 'url' => '/wechat_official_accounts'],
                ['text' => '微信自定义菜单', 'url' => "/wx_app_id/{$wx_app_id}/wechat_menus"]
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

            $content->header('微信自定义菜单');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '公众号管理', 'url' => '/wechat_official_accounts'],
                ['text' => '微信自定义菜单', 'url' => "/wx_app_id/{$wx_app_id}/wechat_menus"],
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
        return Admin::content(function (Content $content) use ($wx_app_id) {

            $content->header('微信自定义菜单');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '公众号管理', 'url' => '/wechat_official_accounts'],
                ['text' => '微信自定义菜单', 'url' => "/wx_app_id/{$wx_app_id}/wechat_menus"],
                ['text' => '新增']
            );

            $content->body($this->form($wx_app_id));
        });
    }

    public function grid($wx_app_id)
    {
        return Admin::grid(WechatMenu::class, function (Grid $grid) use ($wx_app_id) {

            $grid->model()->where("wx_app_id", $wx_app_id);
            $parent = $this->getParent($wx_app_id);
            $type = $this->getType();
            $account = $this->getAccount();
            $grid->column("id","id");
            $grid->column("name","菜单名称");
            $grid->column("parent_id","父菜单")->using($parent);
            $grid->column("type","类型")->using($type);
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","最近更新时间")->sortable();
            $grid->column("status","状态")->using([0=>"冻结",1=>"启用"]);
            $grid->column("wx_app_id","所属公众号")->using($account);

            $grid->tools(function (Grid\Tools $tools) use($wx_app_id){
                $tools->append(new WechatMenusReturn());
                $tools->append(new WechatMenusCreate($wx_app_id));
            });

            $grid->actions(function(Grid\Displayers\Actions $actions)
            {
                $actions->append(new WechatMenuConfig($actions->getResource(), $actions->getKey()));
            });


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter) use ($account){

                $filter->equal("status","状态")->select([0=>"冻结",1=>"启用"]);

                $filter->equal("wx_app_id","所属公众号")->select($account);


            });

        });
    }

    protected function form($wx_app_id)
    {
        return Admin::form(WechatMenu::class, function (Form $form) use ($wx_app_id) {

            $parent = $this->getParent($wx_app_id);
            $type = $this->getType();
            $account = $this->getAccount();
            $form->display('id',"id");
            $form->text('name',"菜单名称")->rules("required|string");
            $form->select('parent_id',"父菜单")->options($parent);
            $form->select('type',"类型")->options($type);
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"最近更新时间");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"])->default(1);

            $form->select('wx_app_id',"所属公众号")->options($account)->default($wx_app_id);

        });
    }

    protected function getAccount()
    {
        $data = WechatOfficialAccount::all();

        $data_options = [];
        foreach($data as $value)
        {
            $data_options[$value['wx_app_id']] = $value['name'];
        }

        return $data_options;
    }

    protected function getParent($wx_app_id)
    {
        $data = WechatMenu::where("wx_app_id", $wx_app_id)->get()->toArray();

        $data_options = [];
        $data_options[0] = "无";
        foreach($data as $value)
        {
            $data_options[$value['id']] = $value['name'];
        }

        return $data_options;
    }

    protected function getType()
    {
        $data = WechatMenuType::all();

        $data_options = [];
        foreach($data as $value)
        {
            $data_options[$value['id']] = $value['name'];
        }

        return $data_options;
    }

    public function update($wx_app_id, $id)
    {
        return $this->form($wx_app_id)->update($id);
    }

    public function destroy($wx_app_id, $id)
    {
        if ($this->form($wx_app_id)->destroy($id)) {
            $data = [
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ];
        } else {
            $data = [
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ];
        }

        return response()->json($data);
    }

    public function menuCreate($wx_app_id)
    {
        $log = myLog("menu_create");
        $sdk = new WechatOfficialService();

        $response = $sdk->createMenu($wx_app_id);

        $log->addDebug("response", $response);
        if($response['errcode'] == 0)
        {
            return response([
                'status'  => true,
                'message' => trans('admin.update_succeeded'),
            ]);
        }else{
            return response([
                'status'  => false,
                'message' => trans('admin.update_succeeded'),
            ]);
        }
    }

    public function store($wx_app_id)
    {
        return $this->form($wx_app_id)->store();
    }

}
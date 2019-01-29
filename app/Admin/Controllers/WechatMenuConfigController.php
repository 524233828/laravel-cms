<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/1/26
 * Time: 8:09
 */

namespace App\Admin\Controllers;


use App\Http\Controllers\Controller;
use App\Models\WechatMenu;
use App\Models\WechatMenuConfig;
use App\Models\WechatMenuTypeOption;
use Encore\Admin\Controllers\HasResourceActions;
use \Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class WechatMenuConfigController extends Controller
{
    use HasResourceActions;

    public function index($menu_id)
    {
        return Admin::content(function (Content $content) use ($menu_id){

            $menu = WechatMenu::where("id", $menu_id)->first();
            $wx_app_id = $menu->wx_app_id;
            //页面描述
            $content->header('微信自定义菜单配置项');
            //小标题
            $content->description('微信自定义菜单配置项');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '公众号管理', 'url' => '/wechat_official_accounts'],
                ['text' => '微信自定义菜单', 'url' => "/wx_app_id/{$wx_app_id}/wechat_menus"],
                ['text' => '微信菜单配置', 'url' => "/wechat_menus/{$menu_id}/configs"]
            );

            $content->body($this->grid($menu_id));
        });
    }

    public function edit($menu_id, $config_id)
    {
        return Admin::content(function (Content $content) use ($menu_id, $config_id) {

            $menu = WechatMenu::where("id", $menu_id)->first();
            $wx_app_id = $menu->wx_app_id;

            list($method, $id) = explode("_", $config_id);

            if($method == "option"){
                $description = "添加";
                $content->body($this->form($menu_id, $config_id));
            }else{
                $description = "编辑";
                $content->body($this->form($menu_id, $config_id)->edit($id));
            }

            $content->header('微信自定义菜单');
            $content->description($description);

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '公众号管理', 'url' => '/wechat_official_accounts'],
                ['text' => '微信自定义菜单', 'url' => "/wx_app_id/{$wx_app_id}/wechat_menus"],
                ['text' => '微信菜单配置', 'url' => "/wechat_menus/{$menu_id}/configs"],
                ['text' => $description]
            );
        });
    }

    public function grid($menu_id)
    {
        return Admin::grid(WechatMenuConfig::class,function (Grid $grid){
            $grid->column("name","配置项描述");
            $grid->column("key","配置项标签");
            $grid->column("value","配置值");
        });
    }

    protected function form($menu_id, $config_id = 0)
    {
        return Admin::form(WechatMenuConfig::class, function (Form $form) use ($menu_id, $config_id) {

            list($method, $id) = explode("_", $config_id);
            if($method == "option")
            {
                $option = WechatMenuTypeOption::where("id", $id)->first();
                $key = $option->key;
            }else{
                $config = WechatMenuConfig::where("id", $id)->first();

                $key = $config->key;
                $option = WechatMenuTypeOption::where("key", $key)->first();
            }

            $name = $option->name;

            $form->display('id',"id");
            $form->hidden('menu_id',"menu_id")->default($menu_id);
            $form->display('name',"配置项")->default($name);
            $form->text('key',"配置项标签")->value($key);
            $form->text('value',"配置值");

            $form->ignore('name');

        });
    }

    public function update($menu_id, $id)
    {
        return $this->form($menu_id, "config_".$id)->update($id);
    }

    public function store($menu_id, $config_id)
    {
        return $this->form($menu_id, $config_id)->store();
    }

    public function show($menu_id, $config_id)
    {
        list($method, $id) = explode("_", $config_id);

        $option = WechatMenuTypeOption::where("id", $id)->first();

        $key = $option->key;

        $config = WechatMenuConfig::where(["key" => $key, "menu_id" => $menu_id])->first();

        $cid = $config->id;
        return $this->edit($menu_id, "config_".$cid);
    }

}
<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-05-11 15:46:58
 */

namespace App\Admin\Controllers;

use App\Models\CmsFriendLink;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use function foo\func;

class FriendLinkController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('友情链接');
            //小标题
            $content->description('友情链接列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '友情链接', 'url' => '/friend_links']
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

            $content->header('友情链接');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '友情链接', 'url' => '/friend_links'],
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

            $content->header('友情链接');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '友情链接', 'url' => '/friend_links'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(CmsFriendLink::class, function (Grid $grid) {

            $grid->column("id","ID");
            $grid->column("name","名称");
            $grid->column("link","链接");
            $grid->column("type","类型")->display(function ($value)
            {
                switch ($value){
                    case 0:
                        return "政务链接";
                    case 1:
                        return "其他链接";
                    default:
                        return "政务链接";
                }
            });
            $grid->column("created_at","创建时间");


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("type","类型")->select([0=>"政务链接", 1=>"其他链接"]);


            });


        });
    }

    protected function form()
    {
        return Admin::form(CmsFriendLink::class, function (Form $form) {

            $form->display('id',"ID");
            $form->text('name',"名称")->rules("required|string");
            $form->text('link',"链接")->rules("required|string");
            $form->select('type',"类型")->options([0=>"政务链接", 1=>"其他链接"])->rules("required|integer");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"更新时间");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"])->default(1);



        });
    }
}
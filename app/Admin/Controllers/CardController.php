<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2018-12-06 23:17:07
 */

namespace App\Admin\Controllers;

use App\Models\Card;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class CardController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('会员卡等级');
            //小标题
            $content->description('等级列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '会员卡等级', 'url' => '/card']
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

            $content->header('会员卡等级');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '会员卡等级', 'url' => '/card'],
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

            $content->header('会员卡等级');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '会员卡等级', 'url' => '/card'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(Card::class, function (Grid $grid) {

            $grid->column("id")->sortable();
            $grid->column("card_name")->sortable();
            $grid->column("image_url");
            $grid->column("desc");
            $grid->column("is_default")->using([0=>"非默认",1=>"默认"]);
            $grid->column("status")->using([0=>"冻结",1=>"启用"]);
            $grid->column("created_at")->sortable();
            $grid->column("updated_at")->sortable();


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->where(function ($query) {
                    $query->where('card_name', 'like', "{$this->input}%");
                }, 'card_name');
                $filter->equal("is_default")->select([0=>"非默认",1=>"默认"]);

                $filter->between("created_at")->datetime();


            });


        });
    }

    protected function form()
    {
        return Admin::form(Card::class, function (Form $form) {

            $form->display('id');
            $form->text('card_name')->rules("required|string");
            $form->text('image_url')->rules("required|string");
            $form->text('desc')->rules("required|string");
            $form->select("is_default")->options([0=>"非默认",1=>"默认"]);

            $form->select("status")->options([0=>"冻结",1=>"启用"]);

            $form->datetime('created_at');
            $form->datetime('updated_at');


        });
    }
}
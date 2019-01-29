<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-01-19 23:38:11
 */

namespace App\Admin\Controllers;

use App\Models\WechatMenuLevel;
use App\Models\WechatMenuLevelType;
use App\Models\WechatMenuType;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class WechatMenuTypeController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('自定义菜单类型');
            //小标题
            $content->description('菜单类型列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '自定义菜单类型', 'url' => '/wechat_menu_types']
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

            $content->header('自定义菜单类型');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '自定义菜单类型', 'url' => '/wechat_menu_types'],
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

            $content->header('自定义菜单类型');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '自定义菜单类型', 'url' => '/wechat_menu_types'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(WechatMenuType::class, function (Grid $grid) {


            $grid->column("id","id");
            $grid->column("name","类型");
            $grid->column("tag","类型标签");
            $grid->column("created_at","创建时间")->sortable();
            $grid->column("updated_at","最近更新时间")->sortable();
            $grid->column("status","状态")->using([0=>"冻结",1=>"启用"]);
            $grid->levels("支持的等级")->display(function ($levels) {

                $levels = array_map(function ($level) {
                    return "<span class='label label-success'>{$level['name']}</span>";
                }, $levels);

                return join('&nbsp;', $levels);
            });
//            $grid->column("levels.name","所属等级");


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->where(function ($query) {
                    $query->where('tag', 'like', "{$this->input}%");
                }, '类型标签');
                $filter->equal("status","状态")->select([0=>"冻结",1=>"启用"]);

            });


        });
    }

    protected function form()
    {
        return Admin::form(WechatMenuType::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('name',"类型")->rules("required|string");
            $form->text('tag',"类型标签")->rules("required|string");
            $form->datetime('created_at',"创建时间");
            $form->datetime('updated_at',"最近更新时间");
            $form->select("status","状态")->options([0=>"冻结",1=>"启用"]);

            $form->multipleSelect('level',"所属等级")->options($this->getLevel());

            $form->ignore('level');
            $form->saved(function(Form $form){
                $id = $form->model()->id;
                WechatMenuLevelType::where("type_id", $id)->delete();
                $data = Input::all();
                $level = [];
                foreach ($data['level'] as $value)
                {
                    if(!empty($value)){
                        $level[] = [
                            "level_id" => $value,
                            "type_id" => $id,
                        ];
                    }
                }

                DB::table("wechat_menu_level_types")
                    ->insert($level);
            });


        });
    }

    protected function getLevel()
    {
        $data = WechatMenuLevel::all();

        $data_options = [];
        foreach($data as $value)
        {
            $data_options[$value['id']] = $value['name'];
        }

        return $data_options;
    }
}
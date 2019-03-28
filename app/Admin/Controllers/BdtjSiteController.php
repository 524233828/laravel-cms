<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-03-26 11:55:40
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\FetchBaiduSites;
use App\Models\BdtjSite;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use function foo\func;

class BdtjSiteController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('站点管理');
            //小标题
            $content->description('站点列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '站点管理', 'url' => '/bdtj_sites']
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

            $content->header('站点管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '站点管理', 'url' => '/bdtj_sites'],
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

            $content->header('站点管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '站点管理', 'url' => '/bdtj_sites'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(BdtjSite::class, function (Grid $grid) {

            $grid->column("site_id","站点id")->sortable();
            $grid->column("create_time","站点创建时间")->sortable();
            $grid->column("domain","域名");
            $grid->column("status","状态")->using([0=>'正常', 1=>"暂停"]);
            $grid->column("created_at","站点拉取时间")->sortable();
            $grid->column("updated_at","最近更新时间")->sortable();

            $grid->tools(function(Grid\Tools $tools){
                $tools->append(new FetchBaiduSites());
            });


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("site_id","站点id");
                $filter->equal("status","状态")->select([0=>'正常', 1=>"暂停"]);



            });


        });
    }

    protected function form()
    {
        return Admin::form(BdtjSite::class, function (Form $form) {

            $form->display('site_id',"站点id");
            $form->datetime('create_time',"站点创建时间");
            $form->text('domain',"域名")->rules("required|string");
            $form->select("status","状态")->options([0=>'正常', 1=>"暂停"]);

            $form->datetime('created_at',"站点拉取时间");
            $form->datetime('updated_at',"最近更新时间");


        });
    }

    public function fetchSite()
    {
        $baiduTongji = resolve('BaiduTongji');

        $result = $baiduTongji->getSiteLists();

        $obj = new BdtjSite();

        foreach ($result as $item){
            $data = $item;

            unset($data['sub_dir_list']);

//            var_dump($data);exit;
            if(!BdtjSite::where("site_id","=",$data['site_id'])->exists()){
                $obj->setRawAttributes($data);

                if($obj->save()){
                    return response([
                        'status'  => false,
                        'message' => trans('admin.update_succeeded'),
                    ]);
                }
            }
        }

        return response([
            'status'  => true,
            'message' => trans('admin.update_succeeded'),
        ]);
    }
}
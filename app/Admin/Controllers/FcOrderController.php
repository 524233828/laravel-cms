<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-03-23 18:32:46
 */

namespace App\Admin\Controllers;

use App\Models\FcOrder;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Database\Eloquent\Builder;

class FcOrderController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('订单管理');
            //小标题
            $content->description('订单列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '订单管理', 'url' => '/fc_order']
            );

            $content->body($this->grid());
        });
    }

//    /**
//     * Edit interface.
//     *
//     * @param $id
//     * @return Content
//     */
//    public function edit($id)
//    {
//        return Admin::content(function (Content $content) use ($id) {
//
//            $content->header('订单管理');
//            $content->description('编辑');
//
//            //面包屑导航，需要获取上层所有分类，根分类固定
//            $content->breadcrumb(
//                ['text' => '首页', 'url' => '/'],
//                ['text' => '订单管理', 'url' => '/fc_order'],
//                ['text' => '编辑']
//            );
//
//            $content->body($this->form()->edit($id));
//        });
//    }

//    /**
//     * Create interface.
//     *
//     * @return Content
//     */
//    public function create()
//    {
//        return Admin::content(function (Content $content) {
//
//            $content->header('订单管理');
//            $content->description('新增');
//
//            //面包屑导航，需要获取上层所有分类，根分类固定
//            $content->breadcrumb(
//                ['text' => '首页', 'url' => '/'],
//                ['text' => '订单管理', 'url' => '/fc_order'],
//                ['text' => '新增']
//            );
//
//            $content->body($this->form());
//        });
//    }

    public function grid()
    {
        return Admin::grid(FcOrder::class, function (Grid $grid) {

            $grid->column("id","ID")->sortable();
            $grid->column("order_id","订单号")->sortable();
            $grid->column("total_fee","总价")->sortable();
            $grid->column("create_time","创建时间")->display(function ($value){
                return date("Y-m-d H:i:s", $value);
            })->sortable();
            $grid->column("pay_time","支付时间")->display(function ($value){
                return date("Y-m-d H:i:s", $value);
            })->sortable();
            $grid->column("channel","渠道标识");
            $grid->column("channels.channel_name","渠道名称");
            $grid->column("status","状态")->using([0=>'未付款',1=>'已付款']);
            $grid->column("info","支付信息");

            $grid->disableCreateButton();
            $grid->actions(function(Grid\Displayers\Actions $action){
                $action->disableDelete();
                $action->disableEdit();
            });
//            $grid->column("paysource","支付来源0-微信 1-小程序");


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

//                $filter->equal("id","ID");
                $filter->where(function ($query) {
                    $query->where('order_id', 'like', "{$this->input}%");
                }, '订单号');

                $filter->where(function (Builder $query){

                    /**
                     * @var Grid\Filter\Where $this
                     */
                    $start_time = strtotime($this->input);

                    $end_time = strtotime($this->input."+1 day");

                    $query->whereBetween('create_time', [$start_time, $end_time]);
                },"创建时间")->datetime(['format' => 'YYYY-MM-DD']);

                $filter->where(function (Builder $query){

                    /**
                     * @var Grid\Filter\Where $this
                     */
                    $start_time = strtotime($this->input);

                    $end_time = strtotime($this->input."+1 day");

                    $query->whereBetween('pay_time', [$start_time, $end_time]);
                },"支付时间")->datetime(['format' => 'YYYY-MM-DD']);

                $filter->where(function (Builder $query) {
                    $query->where('channel', 'like', "{$this->input}%");
                }, '渠道标识');
                $filter->equal("status","状态")->select([0=>'未付款',1=>'已付款']);

            });

        });
    }

    protected function show()
    {
        return Admin::show(FcOrder::class, function (Show $show) {

            $show->field('id',"ID");
            $show->field('order_id',"订单号");
            $show->field('total_fee',"总价");
            $show->field('settlement_total_fee',"settlement_total_fee");
            $show->field('fee_type',"fee_type");
            $show->field('id',"ID");
            $show->field('id',"ID");
            $show->field('id',"ID");
            $show->field('id',"ID");
            $show->field('id',"ID");
            $show->field('id',"ID");

//            $form->display('id',"ID");
//            $form->text('order_id',"订单号")->rules("required|string");
//            $form->text('total_fee',"总价")->rules("required");
//            $form->text('settlement_total_fee',"settlement_total_fee")->rules("required");
//            $form->text('fee_type',"fee_type")->rules("required|string");
//            $form->text('coupon_fee',"coupon_fee")->rules("required");
//            $form->text('bank_type',"bank_type")->rules("required|string");
//            $form->text('create_time',"创建时间")->rules("required|integer");
//            $form->text('pay_time',"支付时间")->rules("required|integer");
//            $form->text('point',"付费点")->rules("required|string");
//            $form->text('product_id',"product_id")->rules("required|integer");
//            $form->text('goods_tag',"goods_tag")->rules("required|string");
//            $form->text('channel',"渠道标识")->rules("required|string");
//            $form->select("status","状态")->options([0=>'未付款',1=>'已付款']);
//
//            $form->text('user_id',"用户ID")->rules("required|integer");
//            $form->text('paysource',"支付来源0-微信 1-小程序")->rules("required|integer");
//            $form->editor('info', '支付信息')->rules("required|string");


        });
    }
}
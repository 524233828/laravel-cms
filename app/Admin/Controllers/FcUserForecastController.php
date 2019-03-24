<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-03-23 20:36:11
 */

namespace App\Admin\Controllers;

use App\Admin\Lang\ForecastExtra;
use App\Models\FcForecast;
use App\Models\FcUserForecast;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Database\Eloquent\Builder;

class FcUserForecastController extends Controller
{

    use HasResourceActions;

    public function index()
    {
        return Admin::content(function (Content $content) {

            //页面描述
            $content->header('测算订单管理');
            //小标题
            $content->description('订单列表');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '测算订单管理', 'url' => '/fc_user_forecast']
            );

            $content->body(function(Row $row){
                $row->column(3,function(Column $column){
                    $column->append("已支付金额：");
                });

                $row->column(3,function(Column $column){
                    $column->append("未支付金额：");
                });

                $row->column(3,function(Column $column){
                    $column->append("已支付订单数：");
                });

                $row->column(3,function(Column $column){
                    $column->append("未支付订单数：");
                });
            });

            $content->body(function(Row $row){
                $row->column(3,function(Column $column){
                    $column->append("pv：");
                });

                $row->column(3,function(Column $column){
                    $column->append("uv：");
                });

                $row->column(3,function(Column $column){
                    $column->append("下单率：");
                });

                $row->column(3,function(Column $column){
                    $column->append("转化率：");
                });
            });

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

            $content->header('测算订单管理');
            $content->description('编辑');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '测算订单管理', 'url' => '/fc_user_forecast'],
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

            $content->header('测算订单管理');
            $content->description('新增');

            //面包屑导航，需要获取上层所有分类，根分类固定
            $content->breadcrumb(
                ['text' => '首页', 'url' => '/'],
                ['text' => '测算订单管理', 'url' => '/fc_user_forecast'],
                ['text' => '新增']
            );

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(FcUserForecast::class, function (Grid $grid) {

            $grid->column("id","id")->sortable();
            $grid->column("forecast.forecast_name","测算");
            $grid->column("order_id","订单号")->sortable();
            $grid->column("extra","用户信息")->display(function ($value)
            {
                $value = json_decode($value, true);

                $lemma = [];

                foreach ($value as $key => $item){
                    $lemma[] = ForecastExtra::translate($key) . "：" . ForecastExtra::translate($item);
                }

                $lemma_str = implode("\n", $lemma);
                return <<<HTML
<pre>
$lemma_str
</pre>
HTML;
            });
            $grid->column("create_time","创建时间")->display(function ($value){
                return date("Y-m-d H:i:s", $value);
            })->sortable();
            $grid->column("update_time","支付时间");
            $grid->column("channel","渠道号");
            $grid->column("status","状态")->using([0=>'未付款',1=>'已付款']);


            //允许筛选的项
            //筛选规则不允许用like，且搜索字段必须为索引字段
            //TODO: 使用模糊查询必须通过搜索引擎，此处请扩展搜索引擎
            $grid->filter(function (Grid\Filter $filter){

                $filter->equal("id","id");
                $filter->equal("forecast_id","测算")->select($this->getForecast());
                $filter->where(function (Builder $query) {

                }, '渠道标识');

                $filter->where(function ($query) {
                    $query->where('order_id', 'like', "{$this->input}%");
                }, '订单号');
                $filter->equal("update_time", "支付时间")->datetime(['format' => 'YYYY-MM-DD']);

                $filter->where(function (Builder $query){

                    /**
                     * @var Grid\Filter\Where $this
                     */
                    $start_time = strtotime($this->input);

                    $end_time = strtotime($this->input."+1 day");

                    $query->whereBetween('update_time', [$start_time, $end_time]);
                },"支付时间")->datetime(['format' => 'YYYY-MM-DD']);
                $filter->equal("status","状态")->select([0=>'未付款',1=>'已付款']);

            });


        });
    }

    protected function form()
    {
        return Admin::form(FcUserForecast::class, function (Form $form) {

            $form->display('id',"id");
            $form->text('uid',"uid")->rules("required|integer");
            $form->text('forecast_id',"测算")->rules("required|integer");
            $form->text('order_id',"订单号")->rules("required|string");
            $form->text('extra',"用户信息")->rules("required|string");
            $form->text('create_time',"创建时间")->rules("required|integer");
            $form->datetime('update_time',"更新时间");
            $form->select("status","状态")->options([0=>'未付款',1=>'已付款']);



        });
    }

    protected function getForecast()
    {
        $data = FcForecast::all();

        $data_options = [];
        foreach($data as $value)
        {
            $data_options[$value['id']] = $value['forecast_name'];
        }

        return $data_options;
    }
}
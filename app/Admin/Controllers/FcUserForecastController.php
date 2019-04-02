<?php

/**
 * Created by JoseChan/Admin/ControllerCreator.
 * User: admin
 * DateTime: 2019-03-23 20:36:11
 */

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\FilterDate;
use App\Admin\Lang\ForecastExtra;
use App\Models\FcForecast;
use App\Models\FcUserForecast;
use App\Http\Controllers\Controller;
use App\Models\Statistic;
use Doctrine\DBAL\Query\QueryBuilder;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FcUserForecastController extends Controller
{

    use HasResourceActions;


    public function index()
    {
        //把当前权限放入容器
        $uid = Admin::user()->id;
        $role = DB::table("admin_role_users")->where(["user_id" => $uid])->get();
        app()->instance("current_role", $role[0]);

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

             $content->body($this->statistic());

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
            $grid->column("forecast_name","测算");
            $grid->column("order_id","订单号")->sortable();
            $grid->column("total_fee","订单价格")->sortable();
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

                $filter->expand = true;
                $filter->column(1/2, function (Grid\Filter $filter){
                    $filter->where(function(\Illuminate\Database\Query\Builder &$query)
                    {
                        /**
                         * @var Grid\Filter\Where $this
                         */
//                    var_dump($this);exit;

                        $query->where(["fc_user_forecast.forecast_id" => $this->input]);

//                    var_dump($query->toSql());
//                    return $query;

                    }, "测算", "forecast_id")->select($this->getForecast());


                    if(!$current_channel = FcUserForecast::getCurrentChannel()){
                        $filter->where(function(\Illuminate\Database\Query\Builder &$query)
                        {

                            $query->where("fc_order.channel", "=", $this->input);

                        }, '渠道标识', "channel");
                    }else{
                        $filter->where(function(\Illuminate\Database\Query\Builder &$query)
                        {

                            $query->where("fc_order.channel", "=", $this->input);

                        }, '渠道标识', "channel")->select($current_channel);
                    }

                });


                $filter->column(1/2, function (Grid\Filter $filter){


                    $filter->where(function(\Illuminate\Database\Query\Builder &$query)
                    {

                        $start_time = strtotime($this->input);


                        $query->where("fc_order.create_time",">=", $start_time);


                    }, "下单时间起", "start_time")->datetime(['format' => 'YYYY-MM-DD']);

                    $filter->where(function(\Illuminate\Database\Query\Builder &$query)
                    {

                        $end_time = strtotime($this->input . "+1 day");

                        $query->where("fc_order.create_time", "<", $end_time);


                    },"下单时间止", "end_time")->datetime(['format' => 'YYYY-MM-DD']);
                });

//                $filter->between("create_time", "下单时间")->datetime(['format' => 'YYYY-MM-DD']);

                $filter->column(1/2, function (Grid\Filter $filter){

                    $filter->where(function(\Illuminate\Database\Query\Builder &$query)
                    {
                        $query->where("fc_order.order_id", "=", $this->input);

//                    return $query;

                    }, '订单号', "order_id");

                    $filter->where(function(\Illuminate\Database\Query\Builder &$query)
                    {
                        $query->where("fc_order.status", "=", $this->input);

//                    return $query;

                    },"状态", "status")->select([0=>'未付款',1=>'已付款']);
                });

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

    public function statistic()
    {
        return Admin::grid(Statistic::class, function(Grid $grid){

            $grid->tools(function (Grid\Tools $tools){
                $tools->append(new FilterDate());
            });

            $grid->disableActions();
            $grid->disableFilter();
            $grid->disableCreateButton();
            $grid->disablePagination();
            $grid->disableExport();
            $grid->disableRowSelector();

            $grid->column("pay_fee", "已支付金额");
            $grid->column("total_fee", "总金额");
            $grid->column("pay_order", "已支付订单数");
            $grid->column("total_order", "总订单数");
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
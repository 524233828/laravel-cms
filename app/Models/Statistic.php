<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-03-26
 * Time: 08:52
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;

class Statistic extends Model
{

    protected $table = "";
    public $query;

    public function paginate()
    {

        $data = [];

        $movies = static::hydrate($data);

        $paginator = new LengthAwarePaginator($movies, 0, 10);

        $paginator->setPath(url()->current());

        return $paginator;
    }

    public static function with($relations)
    {
        return new static;
    }

    public function get()
    {

        $forecast_id = Request::get("forecast_id", null);
        $channel = Request::get("channel", null);
        $create_time = Request::get("create_time", null);
        $pay_time = Request::get("pay_time", null);
        $order_id = Request::get("order_id", null);
        $status = Request::get("status", null);

        if(!($this->query instanceof Builder)){
            $this->query = $this->newBaseQueryBuilder();
        }

        $where = [];
        $statistic_where = [];

        if($my_channels = FcUserForecast::getCurrentChannel())
        {
            $this->query->whereIn("fc_order.channel", $my_channels);
//            $statistic_where[] = ["channel", "IN", "(".implode(",", $channel).")"];
        }


        if(!empty($forecast_id))
        {
            $where[] = ["fc_user_forecast.forecast_id","=",$forecast_id];
            $statistic_where[] = ["forecast_id", "=", $forecast_id];
        }

        if(!empty($channel))
        {
            $where[] = ["fc_order.channel","=",$channel];
            $statistic_where[] = ["channel", "=", $channel];
        }

        if(!empty($create_time))
        {

            $start_time = strtotime($create_time);

            $end_time = strtotime($create_time."+1 day");

            $this->query->whereBetween("fc_order.create_time", [$start_time, $end_time]);
//            $where[] = ["fc_order.create_time","between",[$start_time, $end_time]];
            $statistic_where[] = ["day", "=", date("Ymd", strtotime($create_time))];
        }

        if(!empty($pay_time))
        {
            $start_time = strtotime($pay_time);

            $end_time = strtotime($pay_time."+1 day");

            $this->query->whereBetween("fc_order.pay_time", [$start_time, $end_time]);
            $where[] = ["fc_order.pay_time","=",$pay_time];
            $statistic_where[] = ["day", "=", date("Ymd", strtotime($pay_time))];
        }

        if(!empty($order_id))
        {
            $where[] = ["fc_order.order_id","=",$order_id];
        }

        if(!empty($status))
        {
            $where[] = ["fc_order.status","=",$status];
        }

        //获取本地数据
        $collect = $this->query->from("fc_user_forecast")
            ->where($where)
            ->join("fc_order", "fc_order.order_id","=","fc_user_forecast.order_id")
            ->groupBy("fc_order.status")
            ->selectRaw("count(*) as num,sum(`total_fee`) as total_fee, fc_order.status")->get();


        $pay_fee = 0;
        $non_pay_fee = 0;
        $pay_order = 0;
        $non_pay_order = 0;
        foreach ($collect as $item){
            if($item->status == 1){
                $pay_fee = $item->total_fee;
                $pay_order = $item->num;
            }else{
                $non_pay_fee = $item->total_fee;
                $non_pay_order = $item->num;
            }
        }

        $total_fee = $pay_fee + $non_pay_fee;
        $total_order = $pay_order + $non_pay_order;

        //获取百度统计pv、uv
        $bdtj = BdtjStatistic::where($statistic_where);

        if($my_channels){
            $bdtj->whereIn("channel", $my_channels);
        }

        $bdtj = $bdtj->selectRaw("sum(`pv`) as pv, sum(`uv`) as uv")->get();

        $pv = $bdtj[0]->pv;
        $uv = $bdtj[0]->uv;

        return collect([
            [
                "pay_fee" => $pay_fee,
                "pay_order" => $pay_order,
                "total_fee" => $total_fee,
                "total_order" => $total_order,
            ],
            //第二行的表头
            [
                "pay_fee" => "下单率",
                "total_fee" => "转化率",
                "pay_order" => "pv",
                "total_order" => "uv",
            ],
            [
                "pay_fee" => $uv == 0 ? 0 : bcmul(bcdiv($total_order, $uv, 4), 100,2) . "%",
                "total_fee" => $total_order == 0 ? 0 : bcmul(bcdiv($pay_order, $total_order, 4), 100,2) . "%",
                "pay_order" => $pv,
                "total_order" => $uv,
            ],
        ]);
    }

}
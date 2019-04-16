<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-03-26
 * Time: 08:52
 */

namespace App\Models;


use App\Services\RedisService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;
use Mushan\BaiduTongji\BaiduTongji;

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
        $start_time = Request::get("start_time", date("Y-m-d"));
        $start_time = empty($start_time) ? date("Y-m-d") : $start_time;
        $end_time = Request::get("end_time", date("Y-m-d", strtotime($start_time)));
        $end_time = empty($end_time) ? $start_time : $end_time;
        $order_id = Request::get("order_id", null);
        $status = Request::get("status", null);

        $start_time = strtotime($start_time);

        $end_time = strtotime($end_time . "+1 day");

        if(!($this->query instanceof Builder)){
            $this->query = $this->newBaseQueryBuilder();
        }


        $where = [];

        if($my_channels = FcUserForecast::getCurrentChannel())
        {
            $this->query->whereIn("fc_order.channel", $my_channels);
        }


        if(!empty($forecast_id))
        {
            $where[] = ["fc_user_forecast.forecast_id","=",$forecast_id];
        }

        if(!empty($channel))
        {
            $where[] = ["fc_order.channel","=",$channel];
        }else if(!empty($my_channels)){
            $channel = array_values($my_channels)[0];
        }

        if(!empty($start_time) && !empty($end_time))
        {
            $this->query->whereBetween("fc_order.create_time", [$start_time, $end_time]);
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
//        $bdtj = $bdtj->where($statistic_where);
//
//        $bdtj = $bdtj->selectRaw("sum(`pv`) as pv, sum(`uv`) as uv")->get();
//
//        $pv = is_null($bdtj[0]->pv) ? 0 : $bdtj[0]->pv;
//        $uv = is_null($bdtj[0]->uv) ? 0 : $bdtj[0]->uv;

        $forecast_view = null;
        if(!empty($forecast_id)){
            $forecast = FcForecast::find($forecast_id);
            $forecast_view = $forecast->view_uri;
        }

        $result = $this->getBdtjData(
            date("Ymd", $start_time),
            date("Ymd", $end_time),
            $forecast_view,
            $channel
        );

        $pv = $result['sum'][0][0];
        $uv = $result['sum'][0][1];

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

    public function getBdtjData($start_date,$end_date, $forecast_view_uri = null, $channel = null)
    {
        $key = "bdtj:{$start_date}:{$end_date}";
        $site_id = 13186253;

        $_SERVER['HTTP_USER_AGENT'] = "";
        /**
         * @var BaiduTongji $baiduTongji
         */
        $baiduTongji = resolve('BaiduTongji');

        $option = [
            'site_id' => $site_id,
            'method' => 'visit/toppage/a',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'metrics' => 'pv_count,visitor_count',
        ];

        $search_word = [];

        if(!empty($forecast_view_uri)){
            $search_word[] = "/".$forecast_view_uri."/index";
            $key .= ":{$forecast_view_uri}";
        }

        if(!empty($channel)){
            $search_word[] = $channel;
            $key .= ":{$channel}";
        }

        $redis = new RedisService([
            "hostname" => env('REDIS_HOST', '127.0.0.1'),
            "port" => env('REDIS_PORT', 6379),
            "database" => 1,
            'password' => env('REDIS_PASSWORD', null)
        ]);

        if(!empty($search_word)){
            $option['searchWord'] = implode("/", $search_word);
        }

        if($redis->exists($key)){
            return json_decode($redis->get($key), true);
        }else{

            $result = $baiduTongji->getData($option);

            $result2['sum'] = $result['sum'];

            $redis->setex($key, 604800, json_encode($result2));

            return $result;
        }


    }

}
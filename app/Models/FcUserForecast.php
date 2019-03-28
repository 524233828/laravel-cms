<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/3/23
 * Time: 19:24
 */

namespace App\Models;


use Doctrine\DBAL\Query\QueryBuilder;
use Encore\Admin\Grid\Filter\Where;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class FcUserForecast extends Model
{

    protected $table = "fc_user_forecast";

    /**
     * @var Builder $query;
     */
    protected $query;

    public function order()
    {
        return $this->hasOne(FcOrder::class, "order_id", "order_id");
    }

    public function forecast(){
        return $this->belongsTo(FcForecast::class, "id", "forecast_id");
    }

    public function paginate()
    {
        $perPage = Request::get('per_page', 10);

        $page = Request::get('page', 1);

        $start = ($page-1)*$perPage;

        if(!($this->query instanceof Builder)){
            $this->query = $this->newBaseQueryBuilder();
        }

        //筛选条件
        if($channel = self::getCurrentChannel())
        {
            $this->query->whereIn("fc_order.channel", $channel);
        }

        //计算总数

        $total = $this->query
            ->from("fc_user_forecast")
            ->join("fc_order", "fc_order.order_id","=","fc_user_forecast.order_id")
            ->count();

        //获取数据
        $sql = $this->query
            ->from("fc_user_forecast")
            ->orderBy("fc_user_forecast.create_time", "desc")
            ->offset($start)
            ->limit($perPage)
            ->select([
                "fc_order.order_id",
                "fc_order.create_time",
                "fc_order.pay_time",
                "fc_order.total_fee",
                "fc_order.channel",
                "fc_order.status",
                "fc_order.id",
                "fc_user_forecast.extra",
                "fc_user_forecast.forecast_id"
            ]);

        $result = DB::select($this->query->toSql(), $this->query->getBindings());

        //获取测算类型
        $forecast = $this->getForecast();

        foreach ($result as &$obj)
        {
            $obj->forecast_name = $forecast[$obj->forecast_id];
        }

        $movies = static::hydrate($result);

        $paginator = new LengthAwarePaginator($movies, $total, $perPage);

        $paginator->setPath(url()->current());

        return $paginator;
    }

    public static function with($relations)
    {
        return new static;
    }


    public function where(\Closure $argument)
    {
//        var_dump($argument);exit;
        $perPage = Request::get('per_page', 10);

        $page = Request::get('page', 1);

        $start = ($page-1)*$perPage;

        if(!($this->query instanceof Builder)) {
            $this->query = $this->newBaseQueryBuilder();
        }

        $argument($this->query);

        return $this;

    }

    public function getForecast()
    {
        $data = FcForecast::all();

        $data_options = [];
        foreach($data as $value)
        {
            $data_options[$value['id']] = $value['forecast_name'];
        }

        return $data_options;
    }

    public static function getCurrentChannel()
    {
        $role = resolve("current_role");

        $my_channel = false;
        if($role->role_id == 2) {
            $my_channel = [];
            $channels = DB::table("fc_admin_channels")
                ->where("admin_id", "=", $role->user_id)
                ->get();

            foreach ($channels as $channel) {
                $my_channel[$channel->channel] = $channel->channel;
            }
        }

        return $my_channel;
    }

    public function whereHas($columns){
        foreach ($this->query->wheres as $where)
        {
            if($columns == $where['columns']){
                return true;
            }
        }

        return false;
    }

    public function getColumns($columns){
        foreach ($this->query->wheres as $where)
        {
            if($columns == $where['columns']){
                return $where;
            }
        }

        return false;
    }
}
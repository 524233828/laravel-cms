<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/3/23
 * Time: 19:24
 */

namespace App\Models;


use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class FcUserForecast extends Model
{

    protected $table = "fc_user_forecast";

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

        $query = $this->newBaseQueryBuilder();

//        $sql = "select count(*) as num from `fc_user_forecast` INNER join `fc_order` on `fc_order`.`order_id` = `fc_user_forecast`.`order_id`";

        $total = $query
            ->from("fc_user_forecast")
            ->join("fc_order", "fc_order.order_id","=","fc_user_forecast.order_id")
//            ->where([])
            ->count();

//        $result = DB::select($sql);


//        $total = $result[0]->num;



        $sql = $query
            ->from("fc_user_forecast")
//            ->join("fc_order", "fc_order.order_id","=","fc_user_forecast.order_id")
//            ->where([])
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
            ])
            ->toSql();

        $result = DB::select($sql);

        $movies = static::hydrate($result);

        $paginator = new LengthAwarePaginator($movies, $total, $perPage);

        $paginator->setPath(url()->current());

        return $paginator;
    }

    public static function with($relations)
    {
        return new static;
    }
}
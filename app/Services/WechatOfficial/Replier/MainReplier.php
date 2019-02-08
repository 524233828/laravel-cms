<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/2/8
 * Time: 19:16
 */

namespace App\Services\WechatOfficial\Replier;
use App\Services\WechatOfficial\Constant\UserEventType;


class MainReplier
{

    public function send($app, $params, $type)
    {
        if(isset(UserEventType::$replier[$type]))
        {

            $class = UserEventType::$replier[$type];

            /**
             * @var AbstractReplier $replier
             */
            $replier = new $class();

            return $replier->send($app, $params);
        }
    }
}
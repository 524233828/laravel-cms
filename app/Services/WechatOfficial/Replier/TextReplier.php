<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/2/8
 * Time: 18:03
 */

namespace App\Services\WechatOfficial\Replier;


use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\OfficialAccount\Application;

class TextReplier extends AbstractReplier
{
    /**
     * @param Application $app
     * @param array $params
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     */
    public function send(Application $app, array $params)
    {
        $log = myLog("text_replier");
        $log->addDebug("params", $params);
        $log->addDebug("app".serialize($app));
        $message = new Text($params['content']);
        $result = $app->customer_service->message($message)->send();
        $log->addDebug("result".serialize($result));
        return $result;
    }

    public function getReplied(array $params)
    {
        return new Text($params['content']);
    }
}
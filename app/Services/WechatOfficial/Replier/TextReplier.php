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
        $message = new Text($params['content']);
        return $app->customer_service->message($message)->send();
    }
}
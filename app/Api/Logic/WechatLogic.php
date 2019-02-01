<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/1/18
 * Time: 14:35
 */

namespace App\Api\Logic;


use App\Api\Constant\ErrorCode;
use App\Models\WechatOfficialAccount;
use App\Services\WechatOfficial\Event\Handler\MessageReceivedHandler;
use App\Services\WechatOfficial\WechatOfficialService;

class WechatLogic extends Logic
{

    public function getAccessToken($wx_app_id)
    {

        $sdk = new WechatOfficialService();
        $access_token = $sdk->getAccessToken($wx_app_id);

        if(!$access_token)
        {
            ErrorCode::error(ErrorCode::GET_ACCESS_TOKEN_FAIL);
        }

        return ["access_token" => $access_token];
    }

    public function index($original_id)
    {
        $log = myLog("wechat_logic_index");
        $wx_app_id = WechatOfficialAccount::where("original_id", $original_id)->first()->wx_app_id;

        $sdk = new WechatOfficialService();

        $response = $sdk->handleEvent($wx_app_id, function($message) use($log)
        {
            $log->addDebug("message", $message);

            return "";
        });

        return $response;
    }
}
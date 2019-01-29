<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019/1/18
 * Time: 11:07
 */

namespace App\Api\Controllers;


use App\Api\Logic\WechatLogic;
use Illuminate\Http\Request;

class WechatController extends BaseController
{
    public function getAccessToken(Request $request)
    {
        return $this->response(WechatLogic::getInstance()->getAccessToken(
           $request->get("app_id", "")
        ));
    }


    public function index(Request $request)
    {
        $log = myLog("wechat_index");

        $body = $request->getContent();

        $log->addDebug("body:".$body);
        $log->addDebug("url:".$request->fullUrl());
        echo $request->get("echostr");
    }
}
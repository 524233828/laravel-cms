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
use App\Models\WechatReceivedReply;
use App\Models\WechatReceivedText;
use App\Models\WechatUserEvent;
use App\Services\WechatOfficial\Constant\UserEventType;
use App\Services\WechatOfficial\WechatOfficialService;
use App\User;
use Illuminate\Database\Eloquent\Model;

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

        $wx_app_id = WechatOfficialAccount::where("original_id", $original_id)->first()->wx_app_id;

        $sdk = new WechatOfficialService();

        $response = $sdk->handleEvent($wx_app_id, function($message) use ($wx_app_id)
        {
            $msg_id = isset($message['MsgId']) ? $message['MsgId'] : md5(json_encode($message));

            $event = WechatUserEvent::where("msgid", $msg_id)->first();

            if(!empty($event))
            {

                return "";
            }
            $event = new WechatUserEvent();

            $event->setRawAttributes([
                "msgid" => $msg_id,
                "to_user_name" => $message['ToUserName'],
                "from_user_name" => $message['FromUserName'],
                "msg_type" => $message['MsgType'],
                "create_time" => date("Y-m-d H:i:s", $message['CreateTime']),
                "body" => json_encode($message)
            ]);

            $result = $event->save();
            $log = myLog("wechat_index");
            $log->addDebug("message",$message);
            switch ($message['MsgType'])
            {
                case UserEventType::$alias[UserEventType::TEXT]:

                    $text = $message['Content'];
                    //文字回复只有全匹配和半匹配
                    //全匹配通过数据库直接查询
                    $receiver = WechatReceivedText::where(["wx_app_id" =>$wx_app_id,"type" => 1, "content" => $text])->first();
                    $log->addDebug("receiver".serialize($receiver));
                    if(!empty($receiver))
                    {
                        return $this->replyText($receiver->id, $wx_app_id);
                    }

                    //半匹配需要获取所有当前公众号的配置去匹配
                    $receivers = WechatReceivedText::where(["wx_app_id" =>$wx_app_id,"type" => 0])->all();
                    $log->addDebug("receiver".serialize($receivers));
                    foreach ($receivers as $receiver)
                    {
                        if(strpos($text,$receiver['content'])!==false)
                        {
                            return $this->replyText($receiver->id, $wx_app_id);
                        }
                    }
                    break;
                default:
                    return '';

            }
            

            return "";
        });

        return $response;
    }

    protected function replyText($received_id, $wx_app_id)
    {
        $log = myLog("custom_send");
        //获取所有响应者
        $received_reply = WechatReceivedReply::where("received_id", $received_id)->get()->toArray();
        $log->addDebug("received_reply".json_encode($received_reply));

        //根据类型分类
        $type_index_reply_id = [];
        //类型-响应者ID=>对象数组
        $type_reply_id_index = [];
        foreach($received_reply as $value)
        {
            $type_index_reply_id[$value['type']][] = $value['reply_id'];
            $type_reply_id_index[$value['type']][$value['reply_id']] = $value;
        }

        $log->addDebug("type_index_reply_id".json_encode($type_index_reply_id));
        $log->addDebug("type_reply_id_index".json_encode($type_reply_id_index));
        //获取所有响应者
        $replier = [];
        foreach ($type_index_reply_id as $type => $ids)
        {
            if(isset(UserEventType::$replier_model[$type]))
            {

                $class = UserEventType::$replier_model[$type];
                /**
                 * @var Model $model
                 */
                $model = new $class();
                $log->addDebug("ids".json_encode($ids));
                $reply = $model->whereIn("id", $ids)->get()->all();
                $log->addDebug("reply".json_encode($reply));

                foreach ($reply as &$item)
                {
                    $item['type'] = $type_reply_id_index[$type][$item['id']]['type'];
                    $item['sort'] = $type_reply_id_index[$type][$item['id']]['sort'];
                }

                $replier = array_merge($replier, $reply);

            }
        }
        $log->addDebug("replier:".json_encode($replier));
        //排序
        $replier = array_values(collect($replier)->sortByDesc("sort")->all());

        //发送
        $sdk = new WechatOfficialService();
        foreach ($replier as $item)
        {
            $result = $sdk->sendCustom($wx_app_id, $item['type'], $item);

            $log->addDebug("send_Result:", $result->toArray());
        }

        return "";
    }
}
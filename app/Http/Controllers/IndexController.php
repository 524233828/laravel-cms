<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/11/21
 * Time: 10:22
 */

namespace App\Http\Controllers;


use App\Models\Card;
use App\Models\UserCard;
use EasyWeChat\Factory;
use Illuminate\Http\Request;

class IndexController
{

    protected $config = [
        'debug'  => true,
        'app_id'  => 'wx6443795c16bf53ba',         // AppID
        'secret'  => '4c774350e2330a277417dbb3c91ee132',     // AppSecret
        'log' => [
            'level'      => 'debug',
            'permission' => 0777,
            'file'       => '../runtime/logs/easywechat.log',
        ],

        'oauth' => [
            'scopes'   => ['snsapi_base'],
            'callback' => '/oath/callback',
        ],
    ];

    public function index(Request $request)
    {
        //没有微信ID，静默授权
        if( null === $wxid = $request->get("wxid")){
            return $this->wxOauth();
        }

        $model = new UserCard();
        $user_card = $model->where(["openid" => $wxid])->first();

        if(!$user_card)
        {
            $is_get_card = 0;
            $card = Card::where(["is_default"=> 1])->get();
            $card_array = $card->toArray()[0];
        }else{

            $is_get_card = 1;
            $user_card_array = $user_card->toArray();

            $card = Card::find($user_card_array['card_id']);
            $card_array = $card->toArray();
        }

        $data = [
            "image_url" => $card_array['image_url'],
            "card_no" => isset($user_card_array['card_no']) ? $user_card_array['card_no'] : "",
            "score" => isset($user_card_array['score']) ? $user_card_array['score'] : 0,
            "is_get_card"=>$is_get_card,
            "wxid" => $wxid
        ];

        return view("index", $data);
    }

    public function wxOauth()
    {

        $app = Factory::officialAccount($this->config);

        $oauth = $app->oauth;

        return $oauth->redirect();
    }

    public function oauthCallback()
    {
        $app = Factory::officialAccount($this->config);

        $oauth = $app->oauth;

        $user = $oauth->user();

//        redirect();

    }


}
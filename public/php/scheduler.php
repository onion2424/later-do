<?php

namespace app\public\php;

require_once __DIR__ . '/common_class.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;

//チャネルシークレットとチャネルアクセストークンを以下の変数にセット
$channel_access_token = getenv('CHANNEL_ACCESS_TOKEN');
$channel_secret = getenv('CHANNEL_SECRET');

// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new CurlHTTPClient($channel_access_token);

//CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);

try {
    $connection = new \Connection();

    //SQL実行
    $array = $connection->getSendMessage();
    if ($connection->status) {
        //  送信する
        foreach($array as $rec){
            $to = $rec["userid"];
            $builder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($rec["tasks"]);
            $response = $bot->pushMessage($to, $builder);
        }
        //送信の有無にかかわらず後処理
        $connection->postProcess();
        if(!$connection->status) error_log('Error: 後処理に失敗');
    }else{
        error_log('Error: メッセージの取得に失敗');
    }

} catch (\PDOException $e) {
    error_log(\HttpResponse::getPDOMessage($e));
}

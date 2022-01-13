<?php
// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/vendor/autoload.php';

use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use \LINE\LINEBot\Event;
use LINE\LINEBot\Event\MessageEvent\StickerMessage;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\Event\UnFollowEvent;

//logを出す
error_log("hello! LineBot!!");

//先ほど取得したチャネルシークレットとチャネルアクセストークンを以下の変数にセット
$channel_access_token = 'tSiHKbZECWm5IWDlEb1LB4mppPyavQ+QTkghN2A/0YfJn0mrdBPStOOwVXkmLrrbXp74da7oKUBl21V1BI4KF2Lh1mxnjbB4zRp1J6MTOVYXv0jMnsnxa4bh/+7BAPdVOyYdk9JZIp+6tehysDXl/wdB04t89/1O/w1cDnyilFU=';
$channel_secret = '88b837aed76716fe5d0a55553b914d9d';

// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new CurlHTTPClient($channel_access_token);

//CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);

// LINE Messaging APIがリクエストに付与した署名を取得
$signature = $_SERVER["HTTP_" . HTTPHeader::LINE_SIGNATURE];

$http_request_body = file_get_contents('php://input');

//署名をチェックし、正当であればリクエストをパースし配列へ、不正であれば例外処理
$events = $bot->parseEventRequest($http_request_body, $signature);



foreach ($events as $event) {
    //返信先Token取得
    $reply_token = $event->getReplyToken();
    switch ($event) {
            //友だち登録時/ブロック解除時
        case ($event instanceof FollowEvent):
            //$message = '友だち登録ありがとう';
            //$response = $bot->replyText($replyToken, $message);
            return;

            //フォロー解除イベント(ブロック時)
        case ($event instanceof UnfollowEvent):
            return;

            //スタンプ
        case ($event instanceof StickerMessage):
            //$message = 'スタンプ有り難う';
            //$response = $bot->replyText($replyToken, $message);

            $url = parse_url(getenv('DATABASE_URL'));
            $url = parse_url(getenv('DATABASE_URL'));

            $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));

            $pdo = new PDO($dsn, $url['user'], $url['pass']);
            error_log($pdo->getAttribute(PDO::ATTR_SERVER_VERSION));

            return;
            // メッセージを返信(オウム返し)
        case ($event instanceof TextMessage):
            $message = $event->getText();
            $response = $bot->replyText($reply_token, $message);
            return;
    }
}

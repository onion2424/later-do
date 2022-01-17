<?php
// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__ . '/common_class.php';

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

//チャネルシークレットとチャネルアクセストークンを以下の変数にセット
$channel_access_token = getenv('CHANNEL_ACCESS_TOKEN');
$channel_secret = getenv('CHANNEL_SECRET');

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
    error_log("既読!");
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
            error_log("Stump");
            //ユーザ登録テスト
            //$id = $event->getUserId();
            error_log($id);

            return;
            // メッセージを返信(オウム返し)
        case ($event instanceof TextMessage):
            $message = $event->getText();
            $response = $bot->replyText($reply_token, $message);


            // $url = parse_url(getenv('DATABASE_URL'));

            // $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));

            // $conn = new PDO($dsn, $url['user'], $url['pass']);
            // $sql = 'CALL proc1(?, ?)'; //userID, メッセージ内容
            // //  パラメータをセットする
            // //  =>変数を入れないといけない
            // $id = $event.getUserID(); //https://github.com/line/line-bot-sdk-php/blob/master/src/LINEBot/Event/BaseEvent.php参照
            // $task = $event.getText();
            // $stmt = $conn->prepare($sql);
            // $stmt->bindParam(1, $id, PDO::PARAM_INT);
            // $stmt->bindParam(2, $task, PDO::PARAM_STR);
            // $stmt->execute();
            return;
    }
}

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
use LINE\LINEBot\MessageBuilder;


//チャネルシークレットとチャネルアクセストークンを以下の変数にセット
$channel_access_token = getenv('CHANNEL_ACCESS_TOKEN');
$channel_secret = getenv('CHANNEL_SECRET');

// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new CurlHTTPClient($channel_access_token);

//CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);

// LINE Messaging APIがリクエストに付与した署名を取得
//$signature = $_SERVER["HTTP_" . HTTPHeader::LINE_SIGNATURE];
$signature = $_SERVER["HTTP_X_LINE_SIGNATURE"];

$http_request_body = file_get_contents('php://input');

//署名をチェックし、正当であればリクエストをパースし配列へ、不正であれば例外処理
$events = $bot->parseEventRequest($http_request_body, $signature);

try { //全体を監視
    //DB接続用インスタンス
    $connection = new \Connection();
    //リクエストを受理
    foreach ($events as $event) {
        //スタンプは連打される恐れがあるので先に無視
        if ($event instanceof StickerMessage) continue;

        //返信先Token取得
        $reply_token = $event->getReplyToken();
        switch ($event) {
                //友だち登録時/ブロック解除時
            case ($event instanceof FollowEvent):
                $message = 'ユーザ登録に失敗しました。もう一度追加し直してください。';
                try { //個別に例外処理
                    //ユーザID取得
                    $id = $event->getUserID(); //https://github.com/line/line-bot-sdk-php/blob/master/src/LINEBot/Event/BaseEvent.php参照
                    //SQL実行
                    $connection->userDeposit($id);

                    //成功したらメッセージ
                    if ($connection->status) {
                        $message = 'お友達登録ありがとう！使い方が分からなければ「ヘルプ」と送ってね！デモ登録なので実際に送信はされないよ！';
                    } else { //失敗したらログを残す
                        error_log('ユーザ登録に失敗 : ' . $id);
                    }
                } catch (\PDOException $e) {
                    error_log(\httpResponse::getPDOMessage($e));
                }
                //メッセージを送る
                $response = $bot->replyText($reply_token, $message);
                break;

                //フォロー解除イベント(ブロック時)
            case ($event instanceof UnfollowEvent):
                //何も送れない
                $message = null;
                try {
                    //ユーザID取得
                    $id = $event->getUserID();
                    //SQL実行
                    $connection->userDelete($id);

                    //失敗したらログを残す
                    if (!$connection->status) {
                        error_log('ユーザ削除に失敗 : ' . $id);
                    }
                } catch (\PDOException $e) {
                    error_log(\httpResponse::getPDOMessage($e));
                }
                break;

                // メッセージ受信時
            case ($event instanceof TextMessage):
                if ($event->getText() == 'ヘルプ') {
                    $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
                    // ビルダーにメッセージをすべて追加
                    $message = [
                        'あとでやろうと思ったことをトークで送ってね！約「10分後」,「30分後」,「1時間後」,「3時間後」,「6時間後」,「次の日の朝の6時」のように間隔を空けてお知らせするよ！',
                        '終了したタスクはメニューの一覧から削除できるよ！一覧から今度やるに移した場合は時間が指定できるよ！',
                        '送信タイミングは通信環境によって５分ほど遅れることがあるから注意してね！'
                    ];
                    foreach ($message as $msg) {
                        $builder->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($msg));
                    }
                    $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello');
                    $bot->replyMessage($reply_token, $builder);
                } else {
                    $message = "タスク登録に失敗しました。もう一度送信してください。";
                    try {
                        $id = $event->getUserID(); //ユーザID取得
                        $task = $event->getText(); //メッセージ内容取得
                        //SQL実行
                        $connection->depositTask($id, $task);
                        //SQL実行
                        if ($connection->status) {
                            $message = "登録完了！";
                        }
                    } catch (\PDOException $e) {
                        error_log(\httpResponse::getPDOMessage($e));
                    }
                    //メッセージを送る
                    $response = $bot->replyText($reply_token, $message);
                    break;
                }
            default:
                break;
        }
    }
} catch (\PDOException $e) {
    error_log(\httpResponse::getPDOMessage($e));
} catch (Exception $e) {
    //予期せぬエラー
    error_log('UnExceptError: ' . $e->getMessage());
}
return;

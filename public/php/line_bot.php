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

//データベース情報
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
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
            try {
                $conn = new PDO($dsn, $url['user'], $url['pass']);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $id = $event->getUserID(); //https://github.com/line/line-bot-sdk-php/blob/master/src/LINEBot/Event/BaseEvent.php参照
                $sql = 'CALL userDeposit(?)';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $id, PDO::PARAM_STR);
                $stmt->execute();
                //失敗したらログを残す
                if ($stmt->rowCount() != 1) {
                    error_log('ユーザ登録に失敗 : ' + $id);
                } else {
                    $message = 'お友達登録ありがとう!';
                }
            } catch (\PDOException $e) {
                error_log(httpResponse::getPDOMessage($e));
            }
            //メッセージを送る
            $response = $bot->replyText($reply_token, $message);
            break;

            //フォロー解除イベント(ブロック時)
        case ($event instanceof UnfollowEvent):
            //何も送れない
            $message = null;
            try {
                $conn = new PDO($dsn, $url['user'], $url['pass']);
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $id = $event->getUserID(); //https://github.com/line/line-bot-sdk-php/blob/master/src/LINEBot/Event/BaseEvent.php参照
                $sql = 'CALL userDelete(?)'; //userID
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $id, PDO::PARAM_STR);
                $stmt->execute();
                //失敗したらログを残す
                if ($stmt->rowCount() != 1) {
                    error_log('ユーザ削除に失敗 : ' + $id);
                }
            } catch (\PDOException $e) {
                error_log(httpResponse::getPDOMessage($e));
            }
            break;

            // メッセージ受信時
        case ($event instanceof TextMessage):
            if($event->getText() == 'ヘルプ'){
                $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
                // ビルダーにメッセージをすべて追加
                // $message = [
                //     ['あとでやろうと思ったことをトークで送ってね！約「10分後」,「30分後」,「1時間後」,「3時間後」,「6時間後」,「次の日の朝の6時」にお知らせするよ！'],
                //     ['終了したタスクはメニューの一覧から削除できるよ！']
                // ];
                $message = new \LINE\LineBot\MessageBuilder\TextMessageBuilder('あいうえお');
                $builder->add($message);
                $bot->replyMessage($reply_token, $builder);
            }else{
              $message = "タスク登録に失敗しました。もう一度送信してください。";
              try {
                  $conn = new PDO($dsn, $url['user'], $url['pass']);
                  $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                  $id = $event->getUserID(); //https://github.com/line/line-bot-sdk-php/blob/master/src/LINEBot/Event/BaseEvent.php参照
                  $sql = 'CALL setTask(?, ?)'; //userID, メッセージ内容
                  //  パラメータをセットする
                  //  =>変数を入れないといけない
                  $task = $event->getText();
                  $stmt = $conn->prepare($sql);
                  $stmt->bindParam(1, $id, PDO::PARAM_STR);
                  $stmt->bindParam(2, $task, PDO::PARAM_STR);
                  $stmt->execute();
                  if ($stmt->rowCount() == 1) {
                      $message = "登録完了!";
                  }
              } catch (\PDOException $e) {
                  error_log(httpResponse::getPDOMessage($e));
                }
              //メッセージを送る
              $response = $bot->replyText($reply_token, $message);
              break;
            }
        default :
            break;
    }
}
return;

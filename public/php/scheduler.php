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
    $url = parse_url(getenv('DATABASE_URL'));
    $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
    $conn = new \PDO($dsn, $url['user'], $url['pass']);
    //PDOのエラー時に例外(PDOException)が発生するように設定
    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    //SQL実行
    $sql = 'SELECT * FROM GetSendMessage()';
    $stmt = $conn->prepare($sql);

    //SQL実行
    if ($stmt->execute()) {
        $aryList = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        //  送信する
        foreach($aryList as $rec){
            $to = $rec["userid"];
            $builder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($rec["tasks"]);
            $response = $bot->pushMessage($to, $builder);
        }
        //  送れたかどうかに関わらず更新
        $sql = 'CALL SetSendTime()';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
} catch (\PDOException $e) {
    error_log(\HttpResponse::getPDOMessage($e));
}

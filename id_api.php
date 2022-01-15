<?php


/************POSTされたデータを取得***********/

//  POSTされたJSON文字列を取り出す
$json = file_get_contents("php://input");
////error_log(json_encode($json));


//  JSON文字列をobjectに変換
//      =>trueにしないといけない
$contents = json_decode($json, true);




/***********::messagingAPI通信:**************/

$url = 'https://api.line.me/oauth2/v2.1/verify';

//  APIのために基本情報をセット
$data = [
    'id_token' => $contents['id_token'], // LIFFから送信されたIDトークン
    'client_id' => getenv('LOGIN_CHANNEL_ID'), // LIFFアプリを登録したLINEログインチャネルのチャネルID
];

//  curl実行部分
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$response = curl_exec($ch);
curl_close($ch);



/*********************ユーザ情報取得***********************/

//連想配列に戻す
$userData = json_decode($response, true);

//error_log($response);



/*******:***************返答処理***********************/
//  userIDが存在するかを確認
//      =>subにセットされている
if (!isset($userData['sub']) || $userData['sub'] == "") {

    echo '{tasks : [{"task" : "あいうえお"},{ "task" : "かきくけこ"}]}';
} else { //--------------------データベース接続してデータを取る---------------
    //  ユーザマスタには友達登録時にセットされるはずなのでチェックはしない
    $url = parse_url(getenv('DATABASE_URL'));
    $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
    $conn = new PDO($dsn, $url['user'], $url['pass']);

    $sql = 'CALL GetTasks(?)'; //userIDを入れる
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $userData['sub'], PDO::PARAM_STR);
    $stmt->execute();

    echo '{ "tasks" : [';
    foreach ($stmt as $col) {
        //結果を表示
        echo '{ ';
        echo '"task" : "', $col['task'], '",', PHP_EOL;
        echo '"time" : "', $col['time'], '",', PHP_EOL;
        echo '"count : "', $col['count'], '"', PHP_EOL;
        echo '},';
    }
    echo '] }';
}

echo '';

?>
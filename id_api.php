<?php
$ch = curl_init();

$url = 'https://api.line.me/oauth2/v2.1/verify';



//POSTされたJSON文字列を取り出し
$json = file_get_contents("php://input");
error_log(json_encode($json));
//JSON文字列をobjectに変換
//  =>trueにしないといけない
$contents = json_decode($json, true);

$data = [
    'id_token' => $contents['id_token'], // LIFFから送信されたIDトークン
    'client_id' => getenv('LOGIN_CHANNEL_ID'), // LIFFアプリを登録したLINEログインチャネルのチャネルID
];

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

$response = curl_exec($ch);

curl_close($ch);

//print_r(json_decode($response, true));

//連想配列に戻す
$userData = json_decode($response, true);

error_log($response);

//echo 'name : ', $userData['name'];
echo '{ name : ', 'あいうえお }';

?>
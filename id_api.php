<?php
$ch = curl_init();

$url = 'https://api.line.me/oauth2/v2.1/verify';

error_log(json_encode($_POST['body']));

$data = [
    'id_token' => $_POST['body']['id_token'], // LIFFから送信されたIDトークン
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

echo 'name : ', $userData['name'];

?>
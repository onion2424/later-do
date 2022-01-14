<?php 
include_once("index.html"); 
//http通信の準備
$ch = curl_init();

$url = 'https://api.line.me/oauth2/v2.1/verify';

$date = [
    'id_token' => $idToken,
]

?>
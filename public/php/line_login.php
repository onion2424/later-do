<?php //未使用
define("LINE_CLIENT_ID", )
session_start();
  $code = $_GET['code'];
  echo "Code= " . $code ;

  $callback_url = "https://later-do.herokuapp.com/logged_in";

  $url = sprintf(
      "https://access.line.me/dialog/oauth/weblogin"
      ."?response_type=code"
      ."&client_id=%s"
      ,LINE_CLIENT_ID
      ,$callback_url
  );
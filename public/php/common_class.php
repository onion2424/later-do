<?php
  //共通クラス
  class httpResponse {
    const STATUS_OK = "OK";
    const STATUS_NG = "NG";
    
    //ajax通信時にエラー判断
    public $Status = self::STATUS_NG;

    public $Contents;

    public $message = "";

    static public function getPDOMessage(PDOException $e) {
      return $e->getMessage()." - ".$e->getLine();
    }
  }
  ?>
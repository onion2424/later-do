<?php

  class httpResponse {
    const STATUS_OK = "OK";
    const STATUS_NG = "NG";
    
    //ajax通信時にエラー判断
    public $Status = self::STATUS_OK;

    public $Contents;

    public $message = "";

    public function getPDOMessage(PDOException $e) {
      return $e->getMessage()." - ".$e->getLine();
    }
  }
  ?>
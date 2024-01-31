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

  //DB接続用
  class Connection{
    function __construct(){
        //envから接続情報を取得
        $url = parse_url(getenv('DATABASE_URL'));
        $dsn = sprintf('pgsql:host=%s;dbname=%s;port=%s', $url['host'], substr($url['path'], 1), $url['port']);
        $this->conn = new \PDO($dsn, $url['user'], $url['pass']);
        //PDOのエラー時に例外(PDOException)が発生するように設定
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return;
        postgresql://postgres:onion.6fecfe@db.lftpgdwigcexcrrcpfjj.supabase.co:6543/postgres
    }

    //PDOインスタンス
    private $conn;
    //ステータス管理
    public bool $status;


    //タスク取得
    function getTasks(string $userID):array{
        //ステータスを設定
        $this->status = false;

        //SQL設定
        $sql = 'SELECT * FROM GetTasks(?)'; //userIDを入れる
        $stmt = $this->conn->prepare($sql);

        //パラメータ設定
        $stmt->bindParam(1, $userID, \PDO::PARAM_STR);

        //SQL実行
        if($stmt->execute()){
            $array = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            //ステータスを設定
            $this->status = true;
        }

        //関数を戻す
        return $array ? $array : array();

    }

    //タスク削除
    function deleteTask(string $userID, int $taskNo){
        //ステータスを設定
        $this->status = false;

        //SQL設定
        $sql = 'CALL DeleteTask(?, ?)'; //userID, TaskNo
        $stmt = $this->conn->prepare($sql);

        //パラメータ設定
        $stmt->bindParam(1, $userID, \PDO::PARAM_STR);
        $stmt->bindParam(2, $taskNo, \PDO::PARAM_INT);

        //SQL実行
        if($stmt->execute()){
          //ステータスを設定
          $this->status = true;
        }
        return;
    }

    //タスクトグル
    function toggleTask(string $userID, int $taskNo){
      //ステータスを設定
      $this->status = false;

      //SQL設定
      $sql = 'CALL ToggleTask(?, ?)'; //userID, TaskNo
      $stmt = $this->conn->prepare($sql);

      //パラメータ設定
      $stmt->bindParam(1, $userID, \PDO::PARAM_STR);
      $stmt->bindParam(2, $taskNo, \PDO::PARAM_INT);

      //SQL実行
      if($stmt->execute()){
        //ステータスを設定
        $this->status = true;
      }
      return;
    }

    //時間設定
    function setDateTask(string $userID, int $taskNo, string $time){
     //ステータスを設定
     $this->status = false;

     //SQL設定
     $sql = 'CALL SetSendTime(?, ?, ?)'; //userID, TaskNo, time
     $stmt = $this->conn->prepare($sql);

     //パラメータ設定
     $stmt->bindParam(1, $userID, \PDO::PARAM_STR);
     $stmt->bindParam(2, $taskNo, \PDO::PARAM_INT);
     $stmt->bindParam(3, $time, \PDO::PARAM_STR);

    
     //SQL実行
     if($stmt->execute()){
       //ステータスを設定
       $this->status = true;
     }

    }

    /*************メッセージ送信処理用********** */
    //送信用メッセージ取得
    function getSendMessage(){
      //ステータス設定
      $this->status = false;
      //SQL設定
      $sql = 'SELECT * FROM GetSendMessage()';
      $stmt = $this->conn->prepare($sql);
      //SQL実行
     if($stmt->execute()){
        $array = $stmt->fetchAll(\PDO::FETCH_ASSOC);
       //ステータスを設定
       $this->status = true;
      }
      return $array ? $array : array();
    }

    //送信後後処理
    function postProcess(){
      //ステータス設定
      $this->status = false;

      //SQL設定
      $sql = 'CALL PostProcess()';
      $stmt = $this->conn->prepare($sql);
      //SQL実行
     if($stmt->execute()){
       //ステータスを設定
       $this->status = true;
      }
      return;
    }

    //*************LineBot用******************
    //ユーザ登録
    function userDeposit(string $userID){
      //ステータス設定
      $this->status = false;
      //SQL設定
      $sql = 'CALL userDeposit(?)';
      $stmt = $this->conn->prepare($sql); //userID
      $stmt->bindParam(1, $userID, PDO::PARAM_STR);
      //SQL実行
      if($stmt->execute()){
        //ステータスを設定
        $this->status = true;
      }
       return;
    }

    //ユーザ削除
    function userDelete(string $userID){
      //ステータス設定
      $this->status = false;
      //SQL設定
      $sql = 'CALL UserDelete(?)'; //userID
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(1, $userID, PDO::PARAM_STR);
      //SQL実行
      if($stmt->execute()){
        //ステータスを設定
        $this->status = true;
      }
        return;
    }

    //タスク登録
    function depositTask(string $userID, string $task){
      //ステータス設定
      $this->status = false;
      //SQL設定
      $sql = 'CALL SetTask(?, ?)'; //userID, メッセージ内容
      $stmt = $this->conn->prepare($sql);
      //パラメータ設定
      $stmt->bindParam(1, $userID, PDO::PARAM_STR);
      $stmt->bindParam(2, $task, PDO::PARAM_STR);
      //SQL実行
      if($stmt->execute()){
        //ステータスを設定
        $this->status = true;
      }
      return;
    }
}

  ?>
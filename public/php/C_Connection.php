<?php

namespace app\public\php;
require_once __DIR__ . '/common_class.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class Connection{
    function __construnt(){
        //envから接続情報を取得
        $url = parse_url(getenv('DATABASE_URL'));
        $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
        $this->conn = new \PDO($dsn, $url['user'], $url['pass']);
        //PDOのエラー時に例外(PDOException)が発生するように設定
        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return;
    }

    //PDOインスタンス
    private \PDO $conn;
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
            $aryList = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            //ステータスを設定
            $this->status = true;
        }

        //関数を戻す
        return $aryList ? $aryList : array();

    }

}
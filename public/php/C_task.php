<?php

namespace app\public\php;
require_once __DIR__ . '/common_class.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class C_task
{

    public function getTasks()
    {
        /************POSTされたデータを取得***********/
        //  POSTされたJSON文字列を取り出す
        $json = file_get_contents("php://input");
        ////error_log(json_encode($json));


        //  JSON文字列をobjectに変換
        //      =>trueにしないといけない
        $contents = json_decode($json, true);
        //ユーザーデータ取得
        $userData = self::getUserData($contents['id_token']);

        /*******:***************返答処理***********************/
        //  userIDが存在するかを確認
        //      =>subにセットされている

        $ret = new \httpResponse();

        if (!isset($userData['sub']) || $userData['sub'] == "") {

            //返答の形式
            //$aryList = array(array('task' => 'あいうえお'), array('task' => 'かきくけこ'));
            $ret->message = "ユーザー認証に失敗しました。";
        } else { //--------------------データベース接続してデータを取る---------------
            //  ユーザマスタには友達登録時にセットされるはずなのでチェックはしない
            //  登録してなくてもLINE内でURLを開いたらここを通るのでチェックがいる？ - とりあえず速度優先したいからなし
            try {
                $url = parse_url(getenv('DATABASE_URL'));
                $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
                $conn = new \PDO($dsn, $url['user'], $url['pass']);
                //PDOのエラー時に例外(PDOException)が発生するように設定
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                //SQL実行
                $sql = 'SELECT * FROM GetTasks(?)'; //userIDを入れる
                $stmt = $conn->prepare($sql);

                $stmt->bindParam(1, $userData['sub'], \PDO::PARAM_STR);
                //SQL実行
                if($stmt->execute()){
                    $aryList = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                    //戻り値設定
                    $ret->Contents = $aryList ? $aryList : array();
                    $ret->Status = \httpResponse::STATUS_OK;
                }
            } catch (\PDOException $e) {
                error_log(\HttpResponse::getPDOMessage($e));
                $ret->message = "サーバーとの接続に失敗しました。";
            }
        }

        header("content-type:application/json");
        echo json_encode($ret, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function deleteTask(){
        /************POSTされたデータを取得***********/
        //  POSTされたJSON文字列を取り出す
        $json = file_get_contents("php://input");
        ////error_log(json_encode($json));


        //  JSON文字列をobjectに変換
        //      =>trueにしないといけない
        $contents = json_decode($json, true);
        //ユーザーデータ取得
        $userData = self::getUserData($contents['id_token']);

        /*******:***************返答処理***********************/
        //  userIDが存在するかを確認
        //      =>subにセットされている

        $ret = new \httpResponse();

        if (!isset($userData['sub']) || $userData['sub'] == "") {

            $ret->message = "ユーザー認証に失敗しました。";
        } else { //--------------------データベース接続してデータを取る---------------
            try {
                $url = parse_url(getenv('DATABASE_URL'));
                $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
                $conn = new \PDO($dsn, $url['user'], $url['pass']);
                //PDOのエラー時に例外(PDOException)が発生するように設定
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                //SQL実行
                $sql = 'CALL DELETETASK(?, ?)'; //userID, TaskNo
                $stmt = $conn->prepare($sql);

                $cnt = 0;
                $stmt->bindParam(1, $userData['sub'], \PDO::PARAM_STR);
                $stmt->bindParam(2, $contents['taskNo'], \PDO::PARAM_INT);
                
                //SQL実行
                if($stmt->execute()){
                    $ret->Status = \httpResponse::STATUS_OK;
                }else{
                    $ret->message = "削除に失敗しました。";
                }
                
            } catch (\PDOException $e) {
                error_log(\HttpResponse::getPDOMessage($e));
                $ret->message = "サーバーとの接続に失敗しました。";
            }
        }

        //戻り値設定
        header("content-type:application/json");
        echo json_encode($ret, JSON_UNESCAPED_UNICODE);
        exit();
    }


    //API通信でLINEのユーザデータを取得
    static private function getUserData($id_token){


        /***********::messagingAPI通信:**************/

        $url = 'https://api.line.me/oauth2/v2.1/verify';

        //  APIのために基本情報をセット
        $data = [
            'id_token' => $id_token, // LIFFから送信されたIDトークン
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
        
        return $userData;
    }
}

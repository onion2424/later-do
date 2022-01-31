<?php

namespace app\public\php;

use LINE\LINEBot\Event\MessageEvent\ContentProvider;

require_once __DIR__ . '/common_class.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class C_task
{

    public function getTasks()
    {
        /************POSTされたデータを取得***********/
        //  POSTされたJSON文字列を取り出す
        $json = file_get_contents("php://input");

        //  JSON文字列をobjectに変換
        //      =>trueにしないといけない
        $contents = json_decode($json, true);
        //ユーザーデータ取得
        $userData = self::getUserData($contents['id_token']);

        /*******:***************返答処理***********************/
        //  userIDが存在するかを確認
        //      =>subにセットされている

        //返答用のクラスをインスタンス化
        $ret = new \httpResponse();

        if (!isset($userData['sub']) || $userData['sub'] == "") {

            // ↓返答の正しい形式
            //$array = array(array('task' => 'あいうえお'), array('task' => 'かきくけこ'));
            $ret->message = "ユーザー認証に失敗しました。";
        } else { //--------------------データベース接続してデータを取る---------------
            //  ユーザマスタには友達登録時にセットされるはずなのでチェックはしない
            //  登録してなくてもLINE内でURLを開いたらここを通るのでチェックがいる？ - とりあえず速度優先したいからなし
            try {      
                 //DB接続用のクラスをインスタンス化
                $connection = new \Connection();

                //データを取得
                $array= $connection->getTasks($userData['sub']);
                if($connection->status){
                    //戻り値を設定
                    $ret->Contents = $array;
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

        //  JSON文字列をobjectに変換
        //      =>trueにしないといけない
        $contents = json_decode($json, true);
        //ユーザーデータ取得
        $userData = self::getUserData($contents['id_token']);

        /*******:***************返答処理***********************/
        //  userIDが存在するかを確認
        //      =>subにセットされている

        $ret = new \httpResponse();
        if (!($contents['taskNo'] > 0)){
            $ret->message = "タスクの情報が取得できませんでした。";
        }else if (!isset($userData['sub']) || $userData['sub'] == "") {
            $ret->message = "ユーザー認証に失敗しました。";
        } else { //--------------------データベース接続してデータを取る---------------
            try {
                 //DB接続用のクラスをインスタンス化
                $connection = new \Connection();
                
                //削除を実行する
                $connection->deleteTask($userData['sub'], $contents['taskNo']);
                //SQL実行
                if($connection->status){
                    //データを取り直す
                    $array = $connection->getTasks($userData['sub']);
                    if($connection->status){
                        //戻り値を設定
                        $ret->Contents = $array;
                        $ret->Status = \httpResponse::STATUS_OK;
                    }else{
                        $ret->message = "データ取得に失敗しました。";
                    }
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

    public function toggleTask(){
        /************POSTされたデータを取得***********/
        //  POSTされたJSON文字列を取り出す
        $json = file_get_contents("php://input");


        //  JSON文字列をobjectに変換
        //      =>trueにしないといけない
        $contents = json_decode($json, true);
        //ユーザーデータ取得
        $userData = self::getUserData($contents['id_token']);

        /*******:***************返答処理***********************/
        //  userIDが存在するかを確認
        //      =>subにセットされている

        $ret = new \httpResponse();
        if (!($contents['taskNo'] > 0)){
            $ret->message = "タスクの情報が取得できませんでした。";
        }else if (!isset($userData['sub']) || $userData['sub'] == "") {
            $ret->message = "ユーザー認証に失敗しました。";
        } else { //--------------------データベース接続してデータを取る---------------
            try {
                //DB用インスタンス
                $connection = new \Connection();
                //トグル実行
                $connection->toggleTask($contents['sub'], $contents['taskNo']);
                if($connection->status){
                    //データを取り直して返す
                    //SQL実行
                    $array = $connection->getTasks($contents['sub']);
                    if($connection->status){
                        //戻り値設定
                        $ret->Contents = $array;
                        $ret->Status = \httpResponse::STATUS_OK;
                    }else{
                        $ret->message = "データ取得に失敗しました。";
                    }
                }else{
                    $ret->message = "更新に失敗しました。";
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


    public function setDateTask(){
        /************POSTされたデータを取得***********/
        //  POSTされたJSON文字列を取り出す
        $json = file_get_contents("php://input");


        //  JSON文字列をobjectに変換
        //      =>trueにしないといけない
        $contents = json_decode($json, true);
        //ユーザーデータ取得
        $userData = self::getUserData($contents['id_token']);

        /*******:***************返答処理***********************/
        //  userIDが存在するかを確認
        //      =>subにセットされている

        $ret = new \httpResponse();
        if (!($contents['taskNo'] > 0)){
            $ret->message = "タスクの情報が取得できませんでした。";
        }else if (!isset($userData['sub']) || $userData['sub'] == "") {
            $ret->message = "ユーザー認証に失敗しました。";
        } else { //--------------------データベース接続してデータを取る---------------
            try {
                //DB接続クラスインスタンス化
                $connection = new \Connection();

                //時間設定SQL実行
                $connection->setDateTask($userData['sub'], $contents['taskNo'], $contents['time']);
                if($connection->status){
                    //データを取り直して返す
                    //SQL実行
                    $array = $connection->getTasks($contents['sub']);
                    if($connection->status){
                        //戻り値設定
                        $ret->Contents = $array;
                        $ret->Status = \httpResponse::STATUS_OK;
                    }else{
                        $ret->message = "データ取得に失敗しました。";
                    }
                }else{
                    $ret->message = "更新に失敗しました。";
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

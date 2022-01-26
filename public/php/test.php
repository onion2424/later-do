<?php
namespace app\public\php;
require_once __DIR__ . '/common_class.php';
require_once __DIR__ . '/../../vendor/autoload.php';

try {
    $url = parse_url(getenv('DATABASE_URL'));
    $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
    $conn = new \PDO($dsn, $url['user'], $url['pass']);
    //PDOのエラー時に例外(PDOException)が発生するように設定
    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    //SQL実行
    $sql = 'DELETE FROM T_TODO WHERE taskNo = 1';
    $stmt = $conn->prepare($sql);

    //SQL実行
    $stmt->execute();
} catch (\PDOException $e) {
    error_log($ret->getPDOMessage($e));
}

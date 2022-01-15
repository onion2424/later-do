<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

$router = new AltoRouter();

// スクリプトを直で呼び出す
$router->map('GET|POST', '/', function () {
    require_once __DIR__ . '/../../public/index.html';
});

// laravelっぽくクラスとメソッドを指定する
//$router->map('GET', '/', 'isanasan\Router\Http\Handler\Welcome::get', 'welcome');

$match = $router->match();

if ($match !== false) {
    if (is_callable($match['target'])) {
        $match['target']();
    } else {
        $params = explode("::", $match['target']);
        $action = new $params[0]();
        call_user_func_array(array($action, $params[1]), $match['params']);
    }
} else {
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}
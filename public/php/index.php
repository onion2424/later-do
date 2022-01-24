<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

$router = new AltoRouter();

 //'/'はindexを表示
$router->map('GET|POST', '/', function () {
    require_once __DIR__ . '/../../public/index.html';
});

// '/task'はtask関連
$router->map('POST', '/get-tasks', 'app\public\php\C_task::getTasks', 'get-tasks');
$router->map('POST', '/delete-task', 'app\public\php\C_task::deleteTask', 'delete-tasks');

//  Line_bot
$router->map('POST', '/line-bot', function(){
    require_once __DIR__ . '/line_bot.php';
}, 'line-bot');

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
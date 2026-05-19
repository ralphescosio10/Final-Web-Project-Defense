<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\TaskController;
use App\Controllers\AuthController;

$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
if ($uri === '') { $uri = '/'; }

$method = $_SERVER['REQUEST_METHOD'];

switch ($uri) {
    case '/':
        (new TaskController())->index();
        break;

    case '/store':
        (new TaskController())->store();
        break;

    case '/complete':
        (new TaskController())->complete();
        break;

    case '/delete':
        (new TaskController())->delete();
        break;

    case '/edit':
        (new TaskController())->edit();
        break;

    case '/update':
        (new TaskController())->update();
        break;

    case '/login':
        $auth = new AuthController();
        $method === 'POST' ? $auth->login() : $auth->showLogin();
        break;

    case '/register':
        $auth = new AuthController();
        $method === 'POST' ? $auth->register() : $auth->showRegister();
        break;

    case '/logout':
        (new AuthController())->logout();
        break;

    default:
        http_response_code(404);
        echo '404 - Page Not Found';
        break;
}
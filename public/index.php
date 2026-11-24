<?php

declare(strict_types=1);

use App\Core\Router;
use App\Core\View;

$config = require dirname(__DIR__) . '/src/bootstrap.php';

$view = new View(
    dirname(__DIR__) . '/templates',
    dirname(__DIR__) . '/templates_c',
    $config['app']['env'] !== 'production'
);

$router = new Router();

$router->get('/', function () use ($view) {
    $view->assign('title', 'Главная')->display('home.tpl');
});

$router->notFound(function () use ($view) {
    http_response_code(404);
    $view->assign('title', '404 — страница не найдена')->display('404.tpl');
});

$router->dispatch($_SERVER['REQUEST_URI'] ?? '/');

<?php

use CGRD\Controllers\BaseController;
use CGRD\Core\Renderer;
use CGRD\Core\Router;
use CGRD\Core\Session;
use CGRD\Database\Database;
use CGRD\Database\DatabaseInterface;
use CGRD\Http\Request;
use CGRD\Http\RequestInterface;
use CGRD\Http\Response;
use CGRD\Http\ResponseStream;
use CGRD\Http\ResponseInterface;
use CGRD\Repositories\UserRepository;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;


$params = include_once __DIR__ . '/../config/params.php';
$routes = include_once __DIR__ . '/../config/routes.php';
$databaseConfig = include_once __DIR__ . '/../config/database.php';

return [
    'twig' => function() {
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        return new Environment($loader);
    },

    'params' => function() use ($params) {
        return $params;
    },

    RequestInterface::class => function() {        
        return new Request();
    },

    ResponseInterface::class => function($container) {
        return new Response($container->resolve(ResponseStream::class));
    },
    
    'router' => function() use ($routes) {
        return new Router($routes);
    },

    DatabaseInterface::class => function () use ($databaseConfig) {
        return Database::getInstance($databaseConfig['mysql']);
    },

    'renderer' => function($container) use ($params) {
        return new Renderer($container->resolve('twig'), $params);
    },

    UserRepository::class => function($container) {
        return new UserRepository($container->resolve(DatabaseInterface::class));
    },
];

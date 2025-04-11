<?php

require __DIR__ . '/../vendor/autoload.php';

use CGRD\Core\Container;
use CGRD\Core\Application;

$bindings = require_once __DIR__ . '/../config/container.php';
$container = new Container();
$container->loadBindings($bindings);

$app = new Application($container);

return $app;

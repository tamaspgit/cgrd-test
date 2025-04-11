<?php

use CGRD\Controllers\ArticleController;
use CGRD\Controllers\LoginController;

return    [    
    ['login', '/', [LoginController::class, 'login'], ['GET', 'POST']],
    ['logout', '/logout', [LoginController::class, 'logout'], ['GET', 'POST']],
    ['article.show', '/article', [ArticleController::class, 'show'], ['GET', 'POST']],
    ['article.edit', '/article/edit/{id}', [ArticleController::class, 'edit'], ['GET', 'POST', 'PUT']],
    ['article.delete', '/article/delete/{id}', [ArticleController::class, 'delete'], ['GET','DELETE']],
];

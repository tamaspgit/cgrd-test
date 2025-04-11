<?php

namespace CGRD\Controllers;

use CGRD\Services\ArticleService;
use CGRD\Services\UserService;

class ArticleController extends BaseController {

    public function show()
    {
        $errors = null;
        $userService = $this->container->resolve(UserService::class);
        $articleService = $this->container->resolve(ArticleService::class);        

        if (!$userService->isAuthenticated()) {
            $this->session->flash('warning', 'Please Log In!');

            return $this->redirect('/');
        }

        $user = $userService->getUser();           

        if ($this->request->isPost()) {                       
            $data = ['authorId' => $user->getId()];
            $post = $this->request->getPost();

            $data = array_merge($data, $post);

            $article = $articleService->load($data);
    
            if ($article && $articleService->validate($data)) {   
                $this->session->flash('success', 'News was successfully created!');
                $articleService->save();
            } else {                
                $errors = $articleService->getErrors();                
            }

        }
    
        return $this->view('articles', [
            'articles' => $articleService->findAllByAuthorId($user->getId()),
            'errors' => $errors,
            'articleAction' => 'Create News',
            'submitButtonText' => 'Save',
            'formAction' => '/article'
        ]);
    }

    public function edit($params)
    {                
        if (!isset($params['id'])) {
            return "-";
        }

        $id = (int)$params['id'];        
        $errors = null;
        $userService = $this->container->resolve(UserService::class);
        $articleService = $this->container->resolve(ArticleService::class);   

        if (!$userService->isAuthenticated()) {
            $this->session->flash('warning', 'Please Log In!');

            return $this->redirect('/');
        }

        $article = $articleService->findById($id);        
        if (!$article) {            
            $this->session->flash('warning', 'Please Log In!');

            return $this->redirect('/article');
        }

        $user = $userService->getUser();        

        if ($this->request->isPost()) {                               
            $data = ['id' => $id, 'authorId' => $user->getId()];
            $post = $this->request->getPost();
            $data = array_merge($data, $post);            
            $article = $articleService->load($data);

            if ($article->getAuthorId() !== $user->getId()) {
                return "You can not edit this Article.";
            }
    
            if ($article && $articleService->validate($data)) {      
                
                if ($articleService->update()) {
                    $this->session->flash('success', 'News was successfully changed!');
                } else {
                    $this->session->flash('error', 'News edit failed.');
                }
                                          
                return $this->redirect('/article');
            } else {
                $errors = $articleService->getErrors();                
            }
        }
    
        return $this->view('articles', [
            'articles' => $articleService->findAllByAuthorId($user->getId()),
            'article' => $article,
            'errors' => $errors,
            'articleAction' => 'Edit News',
            'submitButtonText' => 'Save',
            'formAction' => '/article/edit/'
        ]);
    }

    public function delete($params)
    {
        if (!isset($params['id'])) {
            return "-";
        }

        $id = $params['id'];        
        $userService = $this->container->resolve(UserService::class);
        $articleService = $this->container->resolve(ArticleService::class);   

        if (!$userService->isAuthenticated()) {
            $this->session->flash('warning', 'Please Log In!');
            return $this->redirect('/');            
        }


        if ($articleService->delete((int)$id)) {
            $this->session->flash('success', 'News was deleted!');

            return $this->redirect('/article');
        } else {
            $this->session->flash('error', 'Delete of news failed!');

            return $this->redirect('/article'); 
        }
    }
}
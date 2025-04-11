<?php

namespace CGRD\Controllers;

use CGRD\Services\UserService;

class LoginController extends BaseController {

    public function login()
    {            
        $userService = $this->container->resolve(UserService::class);
        $params = [];

        if ($this->request->isPost()) {                                                                        
            if ($userService->login($this->request->getParam('username'), $this->request->getParam('password'))) {
                $this->session->flash('success', 'Login Successfull!');
                return $this->redirect('article');
            }
            $this->session->flash('error', 'Invalid Login Data!');
        }                
    
        return $this->view('login', $params);
    }

    public function logout()
    {    
        $this->session->destroy();
        
        return $this->redirect('/');
    }
}
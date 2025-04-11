<?php 

namespace CGRD\Services;

use CGRD\Core\Session;
use CGRD\Models\User;
use CGRD\Repositories\UserRepository;

class UserService {

    public function __construct(
        private UserRepository $userRepository,
        private User $user,
        private Session $session
    )
    {}

    public function getUserByUsername(string $userName): ?User
    {        
        $result = $this->userRepository->findByUsername($userName);
        
        if (!$result) {            
            return null;
        }

        $this->user->populate($result[0]);

        return $this->user;
    }

    public function getById(int $id): ?User
    {        
        $result = $this->userRepository->findById($id);        
        if (!$result) {                    
            return null;
        } 

        $this->user = $result;

        return $this->user;
    }

    public function login(string $username, string $password): ?User
    {
        $user = $this->userRepository->findByUsername($username);

        if (!$user) {
            return null;
        }

        if (password_verify($password, $user->getPassword())) {
            $this->session->set('userId', $user->getId());

            return $user;
        }

        return null;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function isAuthenticated(): bool
    {                
        $id = $this->session->get('userId');
        
        if (!$id) {
            return false;
        }
        
        $user = $this->getById($id);

        return $id && $user;
    }  
}
<?php

namespace CGRD\Repositories;

use CGRD\Database\Database;
use CGRD\Models\User;

class UserRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?User
    {
        $data = $this->db->query('SELECT * FROM users WHERE id = :id', ['id' => $id]);

        return $data ? $this->mapToUser($data[0]) : null;
    }

    public function findByUsername(string $username): ?User
    {
        $data = $this->db->query('SELECT * FROM users WHERE username = :username', ['username' => $username]);        
        
        return $data ? $this->mapToUser($data[0]) : null;
    }
       
    private function mapToUser(array $data): User
    {              
        $user = new User(); 
        $user->setId($data['id']);
        $user->setUsername($data['username']);
        $user->setPassword($data['password']);
        $user->setEmail($data['email']);
        $user->setCreatedAt($data['created_at']);
        
        return $user;       
    }
}

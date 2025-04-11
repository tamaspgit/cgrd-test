<?php

namespace CGRD\Models;

class User
{
    private ?int $id;

    private string $userName;

    private string $password;

    private string $email;

    private string $createdAt;  

    public function populate(array $userData) {        
        foreach ($userData as $key => $value) {            
            if ($key == 'created_at') {
                $key = 'createdAt';
            }
            $this->$key = $value;
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->userName,
            'password' => $this->password,
            'email' => $this->email,
            'created_at' => $this->createdAt,
        ];
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setUsername($userName)
    {
        $this->userName = $userName;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function setPasswordHash($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }
    
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

}

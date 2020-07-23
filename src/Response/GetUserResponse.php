<?php


namespace App\Response;


class GetUserResponse
{
    public $id;
    public $email;
    public $password;
    public $userRole;

    public function getId(){
        return $this->id;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email): void{
        $this->email = $email;
    }
    public function getPassword(){
        return $this->password;
    }

    public function setPassword($password): void {
        $this->password = $password;
    }

    public function getUserRole(){
        return $this->userRole;
    }

    public function setUserRole($userRole): void{
        $this->userRole = $userRole;
    }
}
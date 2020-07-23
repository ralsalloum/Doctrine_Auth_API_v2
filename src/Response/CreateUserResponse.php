<?php


namespace App\Response;


class CreateUserResponse
{
    public $email;
    public $password;
    public $userRole;

    /**
     * @return mixed
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword(){
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getUserRole(){
        return $this->userRole;
    }

    /**
     * @param mixed $userRoles
     */
    public function setUserRole($userRoles): void {
        $this->userRole = $userRoles;
    }
}
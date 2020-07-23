<?php


namespace App\Service;


use App\AutoMapping;
use App\Entity\User;
use App\Manager\UserManager;
use App\Request\CreateUserRequest;
use App\Response\GetUserResponse;

class UserService
{
    private $userManager;
    private $autoMapping;

    public function __construct(UserManager $userManager, AutoMapping $autoMapping)
    {
        $this->userManager = $userManager;
        $this->autoMapping = $autoMapping;
    }

    public function create($request){
        $result = $this->userManager->create($request);
        $response = $this->autoMapping->map(User::class, CreateUserRequest::class, $result);
        return $response;
    }

    public function getAll(){
        $result = $this->userManager->getAll();
        $response=[];
        if(is_array($result) || is_object($result)) {
            foreach ($result as $row)
                $response[] = $this->autoMapping->map(User::class, GetUserResponse::class, $row);
        }
        return $response;

    }
}
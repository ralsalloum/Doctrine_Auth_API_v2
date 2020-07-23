<?php


namespace App\Manager;


use App\AutoMapping;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\CreateUserRequest;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    private $entityManager;
    private $userRepository;
    private $autoMapping;

    public function __construct(EntityManagerInterface $entityManager, AutoMapping $autoMapping, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->autoMapping=$autoMapping;
        $this->userRepository = $userRepository;
    }

    public function create(CreateUserRequest $createUserRequest){
        $userEntity = $this->autoMapping->map(CreateUserRequest::class, User::class, $createUserRequest);
        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
        $this->entityManager->clear();
        return $userEntity;
    }

    public function getAll(){
        $data = $this->userRepository->getAll();
        return $data;
    }
}
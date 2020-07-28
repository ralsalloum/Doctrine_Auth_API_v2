<?php

namespace App\Controller;

use App\AutoMapping;
use App\Request\CreateUserRequest;
use App\Service\UserService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class UserController extends BaseController
{
    private $userService;
    private $autoMapping;


    public function __construct(UserService $userService, AutoMapping $autoMapping)
    {
        $this->userService = $userService;
        $this->autoMapping = $autoMapping;
    }

    /**
     * @Route("/register", name="api_register", methods={"POST"})
     */
    public function register(Request $request)
    {
        try {

            $data = json_decode($request->getContent(), true);
            $request = $this->autoMapping->map(\stdClass::class, CreateUserRequest::class,(object)$data);
            $result = $this->userService->create($request);

            return $this->response($result, self::CREATE);
        }
        catch (UniqueConstraintViolationException $e){
            $errors[] = "The email provided already exists for an account!";
        }
    }

    /**
     * @Route("/login", name="api_login", methods={"POST"})
     */
    public function login()
    {
        return $this->json(['result' => true]);
    }

    /**
     * @Route("/profile", name="api_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile()
    {
        return $this->json(['user'=>$this->getUser()], 200, [], ['groups'=>['api']]);
    }

    /**
     * @Route("/", name="api_home")
     */
    public function home()
    {
        return $this->json(['result'=>true]);
    }

    /**
     * @Route("/getAllUsers", name="api_get_users", methods={"GET"})
     * @return JsonResponse
     */
    public function getAllUsers()
    {
        $result = $this->userService->getAll();
        return $this->response($result, self::FETCH);

    }
}

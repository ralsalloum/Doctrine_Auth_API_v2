<?php

namespace App\Controller;

use App\AutoMapping;
use App\Request\CreateUserRequest;
use App\Service\UserService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Self_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerInterface;


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
    public function register(UserPasswordEncoderInterface $encoder, Request $request){

        //$data = json_decode($request->getContent(), true);
        //$request = $this->autoMapping->map(\stdClass::class, CreateUserRequest::class,(object)$data);
        //$result = $this->userService->create($request);
        //return $this->response($data, self::CREATE);

        //$em = $this->getDoctrine()->getManager();
        //$user = new User();

        //$email = $request->request->get('email');
        $password = $request->request->get('password');
        //$passwordConfirmation = $request->request->get('password_confirmation');

        $errors = [];

        /*if($password != $passwordConfirmation){
            $errors[] = "The password and its confirmation are not matched";
        }*/

        if(strlen($password) < 6){
            $errors[] = "The password should be at least 6 characters";
        }

        if(!$errors){
            //$ep = $encoder->encodePassword($this, $password);

            try {

                $data = json_decode($request->getContent(), true);
                //$request = $this->autoMapping->map(\stdClass::class, CreateUserRequest::class,(object)$data);
                //$result = $this->userService->create($request);
                return $this->respond($data);

                //return $this->json(['user'=>$user]);
            }
            catch (UniqueConstraintViolationException $e){
                $errors[] = "The eamil provided already exists for an account!";
            }
            /*catch (\Exception $e){
                $errors[] = "Can not save the user at this time!";
            }*/

        }

                return $this->json(['error'=>$errors], 400);
    }

    /**
     * @Route("/login", name="api_login", methods={"POST"})
     */
    public function login(){
        return $this->json(['result' => true]);
    }

    /**
     * @Route("/profile", name="api_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(){
        return $this->json(['user'=>$this->getUser()], 200, [], ['groups'=>['api']]);
    }

    /**
     * @Route("/", name="api_home")
     */
    public function home(){
        return $this->json(['result'=>true]);
    }

    /**
     * @Route("/getAllUsers", name="api_get_users", methods={"GET"})
     * @return JsonResponse
     */
    public function getAllUsers(){
        $result = $this->userService->getAll();
        return $this->response($result, self::FETCH);
        /*$users = $this->getDoctrine()->getRepository(User::class)->findAll();

        if(!$users){
            return $this->json(['error'=>'No users found!']);
        }

        return $this->json(['All USERS'=>$users]);*/
    }
}

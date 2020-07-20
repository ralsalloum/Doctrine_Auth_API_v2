<?php

namespace App\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/register", name="api_register", methods={"POST"})
     */
    public function register(UserPasswordEncoderInterface $encoder, Request $request){

        $em = $this->getDoctrine()->getManager();
        $user = new User();

        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $passwordConfirmation = $request->request->get('password_confirmation');

        $errors = [];

        if($password != $passwordConfirmation){
            $errors[] = "The password and its confirmation are not matched";
        }

        if(strlen($password) < 6){
            $errors[] = "The password should be at least 6 characters";
        }

        if(!$errors){
            $ep = $encoder->encodePassword($user, $password);
            $user->setEmail($email);
            $user->setPassword($ep);

            try {
                $em->persist($user);
                $em->flush();

                return $this->json(['user'=>$user]);
            }
            catch (UniqueConstraintViolationException $e){
                $errors[] = "The eamil provided already exists for an account!";
            }
            catch (\Exception $e){
                $errors[] = "Can not save the user at this time!";
            }

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
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $entityManager;
    function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        //dd($user);
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
           // $password = $user->getPassword();
            $hashed_password = $passwordHasher->hashPassword($user,$user->getPassword());
            $user->setPassword($hashed_password);
            //dd($user);
            
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        

        return $this->render('register/index.html.twig',[
            "form" => $form->createView()
        ]);
    }
}

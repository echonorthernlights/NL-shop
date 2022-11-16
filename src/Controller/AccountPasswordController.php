<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{

    private $entityManager;
    function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/account/password', name: 'account_password')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);
        $notification = $alertType = '';

        if($form->isSubmitted() && $form->isValid()){
            $old_pwd = $form->get('old_password')->getData();
            if($passwordHasher->isPasswordValid($user, $old_pwd)){
                $new_pwd = $form->get('new_password')->getData();
                $new_hashed_password = $passwordHasher->hashPassword($user,$new_pwd);
                $user->setPassword($new_hashed_password);

                //$this->entityManager->persist($user);
                $this->entityManager->flush();
                $notification = 'Password updated successfully';
                $alertType = 'success';
            }else{
                $notification = 'Current password is incorrect, please try again.';
                $alertType = 'danger';
            }
        }
        return $this->render('account/password.html.twig',['form'=>$form->createView(),'notification'=>$notification, 'alertType'=>$alertType]);
    }
}

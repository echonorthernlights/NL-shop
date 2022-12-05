<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\UClass\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

  
    #[Route('/account/addresses', name: 'account_address')]
    public function index(): Response
    {
        
        return $this->render('account/address.html.twig');
    }


    #[Route('/account/addresses/add', name: 'account_new_address')]
    public function addAddress(Request $request, Cart $cart): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $address->setUser($this->getUser());
            //dd($address);
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            if($cart->get()){
                return $this->redirectToRoute('order');
            }else{
                return $this->redirectToRoute('account_address');
            }
           
        }

        return $this->render('account/address_form.html.twig', ['form' => $form->createView()]);
        
    }

    #[Route('/account/addresses/edit/{id}', name: 'account_edit_address')]
    public function editAddress(Request $request, $id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if(!$address || $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $address->setUser($this->getUser());
            // //dd($address);
            // $this->entityManager->persist($address);
            $this->entityManager->flush();
            return $this->redirectToRoute('account_address');
        }

        

        return $this->render('account/address_form.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/account/addresses/remove/{id}', name: 'account_remove_address')]
    public function removeAddress($id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if($address && $address->getUser() == $this->getUser()){
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('account_address');
    }



}

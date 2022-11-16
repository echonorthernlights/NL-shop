<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\UClass\Cart;

class CartController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/cart', name: 'cart')]
    public function index(Cart $cart): Response
    {
  
       
       //dd($cartComplete);
        return $this->render('cart/index.html.twig', [
         'cart'=>$cart->getFullCart(),
        ]);
    }


    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(Cart $cart, $id): Response
    {
       $cart->add($id);
        return $this->redirectToRoute('cart');
    }


    #[Route('/cart/remove', name: 'cart_remove')]
    public function remove(Cart $cart): Response
    {
       $cart->remove();
         return $this->redirectToRoute('products');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove_product')]
    public function removeProduct(Cart $cart, $id): Response
    {
       $cart->removeProduct($id);
         return $this->redirectToRoute('cart');
    }


    #[Route('/cart/decrease/{id}', name: 'cart_decrease_quantity')]
    public function decreaseQuantity(Cart $cart, $id): Response
    {

       $cart->decreaseQuantity($id);
         return $this->redirectToRoute('cart');
    }


}

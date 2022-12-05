<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\UClass\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    #[Route('/create-session/{ref}', name: 'stripe')]
    public function index(EntityManagerInterface $entityManager, Cart $cart, $ref)
    {
        
        $stripe_products = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneByRef($ref);

        if(!$order){
          new JsonResponse(["error" => "order"]);
        }

  

        Stripe::setApiKey('sk_test_51IBJKTE4dQvsxQND5aSGFX0Q97lPB1478yYx3ADF6RkvPJsWCVLguAcRGwhM4dZUyHWVQt9q2rljZnpOGgwrcNyj00LaNv9DZG');

        header('Content-Type: application/json');

        foreach($order->getOrderDetails()->getValues() as $product){   
          $product_obj = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
                  
            $stripe_products[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                      'name' => $product->getProduct(),
                      'images' => [$YOUR_DOMAIN.'/uploads/products/'.$product_obj->getImage(),],
                    ],
                    'unit_amount' => $product->getPrice(),
                  ],
                  'quantity' => $product->getQuantity(),
                ];         

                
        }

        $stripe_products[] = [
          'price_data' => [
              'currency' => 'usd',
              'product_data' => [
                'name' => $order->getCarrierName(),
                'images' => [$YOUR_DOMAIN],
              ],
              'unit_amount' => $order->getCarrierPrice() * 100,
            ],
            'quantity' => 1,
          ];     
        

        $checkout_session = Session::create([
          'customer_email'=>$this->getUser()->getEmail(),
            'line_items' => [
                $stripe_products
              ],
              'mode' => 'payment',
              'success_url' => $YOUR_DOMAIN.'/order/thankyou/{CHECKOUT_SESSION_ID}',
              'cancel_url' => $YOUR_DOMAIN.'/order/error/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();
        $response = new JsonResponse(['id'=>$checkout_session->id]);
        return $response;

    }
}

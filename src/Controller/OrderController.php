<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\UClass\Cart;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/order', name: 'order')]
    public function index(Request $request, Cart $cart): Response
    {
        //dd($this->getUser()->getAddresses()->getValues());

        if(!$this->getUser()->getAddresses()->getValues()){
            return $this->redirectToRoute('account_new_address');
        }
        $form = $this->createForm(OrderType::class, null, ["user"=>$this->getUser()]);
        // $form->handleRequest($request);
        // if($form->isSubmitted() && $form->isValid()){
        //     dd($form->getData());
        // }

        return $this->render('order/index.html.twig',["form"=>$form->createView(),"cart"=>$cart->getFullCart()]);
    }

    #[Route('/order/summary', name: 'order_summary'/*,  methods: ['POST']*/)]
    public function add(Request $request, Cart $cart): Response
    {
        $form = $this->createForm(OrderType::class, null, ["user"=>$this->getUser()]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // save order Order()
            $date = new DateTimeImmutable();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstName() .''.$delivery->getLastName();
            $delivery_content.='<br/>'. $delivery->getPhone();
            if($delivery->getCompany()){
                $delivery_content.='<br/>'. $delivery->getCompany();
            }
            $delivery_content.='<br/>'. $delivery->getAddress();
            $delivery_content.='<br/>'. $delivery->getZipCode() .''.$delivery->getCity();
            $delivery_content.='<br/>'. $delivery->getCountry();
            
            //dd($delivery_content);
            $order = new Order();
            $ref = $date->format('dmY').'-'.uniqid();
            $order->setRef($ref);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->entityManager->persist($order);

          
            
            // save order details OrderDetail()
            foreach($cart->getFullCart() as $product){
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                
                $this->entityManager->persist($orderDetails);
                

            }
            $this->entityManager->flush();


            return $this->render('order/add.html.twig',["form"=>$form->createView(),
                                                        "cart"=>$cart->getFullCart(),
                                                        "delivery" => $delivery_content,
                                                        "carrier"=>$carriers,
                                                        "ref"=>$order->getRef()]);
            
        }
        return $this->redirectToRoute('cart');
       
    }
}

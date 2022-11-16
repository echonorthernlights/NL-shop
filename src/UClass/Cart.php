<?php
namespace App\UClass;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class Cart{

    private $requestStack;
    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }
    public function add($id){

        $session = $this->requestStack->getSession();

        $cart = $session->get('cart');
        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
        $session->set('cart',$cart);
    }

    public function get(){

        $session = $this->requestStack->getSession();

        return $session->get('cart');
    }

    public function remove(){

        $session = $this->requestStack->getSession();
        $session->remove('cart');
        return $session->get('cart');
    }

    public function removeProduct($id){

        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        unset($cart[$id]);
        //dd($cart);
        return $session->set('cart',$cart);
    }

    public function decreaseQuantity($id) {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        if(!empty($cart[$id]) && $cart[$id] > 1){
            $cart[$id]--;
        }else{
            unset($cart[$id]);
        }
        return $session->set('cart',$cart);
    }

    public function getFullCart(){
        $cartComplete = [];
        if($this->get()){
         foreach( $this->get() as $id => $quantity){
        $product_obj = $this->entityManager->getRepository(Product::class)->findOneById($id);
        if(!$product_obj){
            $this->removeProduct($id);
            continue;
        }
         $cartComplete[] = [
             'product' => $product_obj,
             'quantity' => $quantity,
         ];
        }
        }
        return $cartComplete;
    }
}
?>
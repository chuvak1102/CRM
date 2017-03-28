<?php

namespace EnterpriseBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use EnterpriseBundle\Entity\Catalog as Product;
use EnterpriseBundle\Entity\Order;

/**
 * @Route("/cart")
 */
class CartController extends Controller
{

    /**
     * @Route("/")
     */
    public function indexAction(Request $request){

        $session = $request->getSession();
        $cart = $session->get('cart');
        $previousOrder = $session->get('order');
        $pRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Catalog');
        $total = 0;

        if(!empty($cart)){
            foreach($cart as $id => $count){

                $product = $pRepo->findOneBy(array('id' => $id));
                $products[] = array(
                    'product' => $product,
                    'count' => $count,
                    'total' => $this->getTotalPrice($cart),
                    'order' => $previousOrder
                );

                $total = $total + $product->getPrice()*$count;
            }

        } else {

            return $this->render(':default:cart.html.twig', array(
                'cartEmpty' => true,
                'order' => $previousOrder
            ));
        }

        return $this->render(':default:cart.html.twig', array(
            'cart' => $products,
            'total' => $total,
            'cartEmpty' => false,
            'order' => $previousOrder
        ));
    }

    /**
     * @Route("/add/{id}", requirements={"id":"[\d]+"})
     */
    public function addToCartAction(Product $product, Request $request){

        $session = $request->getSession();
        $cart = $session->get('cart');
        $session->remove('order');

        if(!empty($cart)){

            foreach($cart as $id => $count){
                if($id == $product->getId()){
                    $cart[$product->getId()] = ++$count;
                } else {
                    $cart[$product->getId()] = 1;
                }
            }

        } else {
            $cart[$product->getId()] = 1;
        }

        $session->set('cart', $cart);
//        $session->remove('cart');
        return new JsonResponse(array('product' => array(
            'count' => count($cart),
        )));
    }


    /**
     * @Route("/plus/{id}", requirements={"id":"[\d]+"})
     */
    public function cartPlusAction(Product $product, Request $request){

        $session = $request->getSession();
        $cart = $session->get('cart');
        $cart[$product->getId()] = ++$cart[$product->getId()];
        $session->set('cart', $cart);
//        $session->remove('cart');
        return new JsonResponse(array('product' => array(
            'count' => count($cart),
            'single' => $cart[$product->getId()],
            'price' => $product->getPrice(),
            'total' => $this->getTotalPrice($cart)
        )));
    }

    /**
     * @Route("/minus/{id}", requirements={"id":"[\d]+"})
     */
    public function cartMinusAction(Product $product, Request $request){

        $session = $request->getSession();
        $cart = $session->get('cart');

        if ($cart[$product->getId()] > 1) {
            $cart[$product->getId()] = --$cart[$product->getId()];
        } else {
            $cart[$product->getId()] = 1;
        }

        $session->set('cart', $cart);
//        $session->remove('cart');
        return new JsonResponse(array('product' => array(
            'count' => count($cart),
            'single' => $cart[$product->getId()],
            'price' => $product->getPrice(),
            'total' => $this->getTotalPrice($cart)
        )));
    }

    /**
     * @Route("/removefromcart/{id}", requirements={"id":"[\d]+"})
     */
    public function removeAction(Product $product, Request $request){

        $session = $request->getSession();
        $cart = $session->get('cart');
        unset($cart[$product->getId()]);
        $session->set('cart', $cart);
//        $session->remove('cart');
        return $this->redirectToRoute('enterprise_front_cart_index');
    }

    protected function getTotalPrice($cart){

        $pRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Catalog');
        $total = 0;

        if(!empty($cart)){
            foreach($cart as $id => $count) {
                $price = $pRepo->findOneBy(array('id' => $id))->getPrice();
                $total = $total + $price*$count;
            }

            return $total;

        } else {
            return 0;
        }
    }

    /**
     * @Route("/order")
     */
    public function orderAction(Request $request){

        $order = new Order;
        $order->setName($request->get('comment'));
        $order->setPhone($request->get('phone'));
        $order->setDeliveryType($this->getDeliveryType($request->get('delivery')));
        $order->setPayType($this->getPayType($request->get('pay')));
        $order->setComment($request->get('comment'));
        $order->setProducts($request->get('products'));
        $order->setOrderId(rand(100, 999).'-'.rand(1000, 9999));
        $order->setPrice($this->getTotalPrice($request->get('products')));
        $order->setCreated(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        $session = $request->getSession();
        $session->remove('cart');
        $session->set('order', $order->getOrderId());

        return new JsonResponse(array('orderId' => $order->getOrderId()));
    }

    protected function getDeliveryType($id){
        if($id == 0){
            return 'Не указан';
        } elseif ($id == 1){
            return 'Доставка курьером';
        } elseif ($id == 2){
            return 'Самовывоз со склада';
        } elseif ($id == 3){
            return 'Другой способ';
        } else {
            return 'Нет такого способа';
        }
    }

    protected function getPayType($id){
        if($id == 0){
            return 'Не указан';
        } elseif ($id == 1){
            return 'Оплата наличными';
        } elseif ($id == 2){
            return 'Оплата по карте';
        } elseif ($id == 3){
            return 'Другой способ';
        } else {
            return 'Нет такого способа';
        }
    }

}


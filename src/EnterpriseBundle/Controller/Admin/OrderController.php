<?php

namespace EnterpriseBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use EnterpriseBundle\Entity\Order;

/**
 * Class MessagesController
 * @package EnterpriseBundle\Controller\Admin
 * @Route("admin/order")
 */
class OrderController extends Controller
{

    /**
     * @Route("/")
     */
    public function indexAction(){

        $oRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Order');
        $pRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Catalog');
        $sRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:SellerProduct');

        if($allOrders = $oRepo->findAll()){

            foreach($allOrders as $order){

                foreach($order->getProducts() as $id => $count){
                    $product = $pRepo->findOneBy(array('id' => $id));
                    $products[] = array(
                        'count' => $count,
                        'item' => $product,
                        'analogs' => $sRepo->getAnalogs($product->getName())
                    );
                };

                if(empty($products)) $products = null;

                $orders[] = array(
                    'products' => $products,
                    'order' => $order
                );

                unset($products);
            }
        }

        if(empty($orders)) $orders = null;

        return $this->render('EnterpriseBundle:Default:order.html.twig', array(
            'orders' => $orders
        ));
    }

    public function mailToAdminAction($order){

        $message = \Swift_Message::newInstance()
            ->setSubject('Новый заказ в магазине polshishki.com')
            ->setFrom('polshishki.com@gmail.com')
            ->setTo('dan0@mail.ru')
            ->setBody(
                $this->renderView('EnterpriseBundle:Default/email:mail-to-admin.html.twig',
                    array('order' => $order)
                ), 'text/html');

        $this->get('mailer')->send($message);

        return new JsonResponse(array('ok' => true));
    }

    /**
     * @Route("/create-order")
     */
    public function createOrder(Request $request){

        $oRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Order');
        $order = $oRepo->findOneBy(array('order_id' => $request->get('orderId')));

        $actualOrder = $request->get('order');
        foreach($actualOrder as $a){
            $vendorData[] = array(
                'vendor' => $a[1],
                'count' => $a[0]
            );
        }

        if(empty($vendorData)) $vendorData = null;

        $this->mailToAdminAction($vendorData);

        $em = $this->getDoctrine()->getManager();
        $order->setComplete(true);
        $em->persist($order);
        $em->flush();

        return $this->redirectToRoute('enterprise_admin_order_index');
    }


}
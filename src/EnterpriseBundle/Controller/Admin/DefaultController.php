<?php

namespace EnterpriseBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class MessagesController
 * @package EnterpriseBundle\Controller
 * @Route("/admin")
 */
class DefaultController extends Controller
{

    private function getCurrUser(){
        return $this->get('security.context')
            ->getToken()
            ->getUser();
    }

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $user = $this->getCurrUser();

        if($user == 'anon.'){

            return $this->redirectToRoute('fos_user_security_login');
        } else {

            return $this->render('EnterpriseBundle:Default:base.html.twig', array(
                'user' => $user->getId()
            ));
        }
    }

    /**
     * @Route("/default")
     */
    public function defaultAction(){

        return $this->render('::base.html.twig');

    }

    /**
     * @Route("/workers")
     */
    public function workersAction(Request $request){
        if($request->isXmlHttpRequest()){
            return $this->render('EnterpriseBundle:Default:workers.html.twig');
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

    /**
     * @Route("/card")
     */
    public function cardAction(Request $request){
        if($request->isXmlHttpRequest()){
            return $this->render('EnterpriseBundle:Default:card.html.twig');
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

    /**
     * @Route("/delivery")
     */
    public function deliveryAction(Request $request){
        if($request->isXmlHttpRequest()){
            return $this->render('EnterpriseBundle:Default:delivery.html.twig');
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

    /**
     * @Route("/groups")
     */
    public function groupsAction(Request $request){
        if($request->isXmlHttpRequest()){
            return $this->render('EnterpriseBundle:Default:groups.html.twig');
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

    /**
     * @Route("/important")
     */
    public function importantAction(Request $request){
        if($request->isXmlHttpRequest()){
            $redis = $this->container->get('snc_redis.default');
            $d1 = new \DateTime();
            $d2 = $d1->format('s');
//            for($i = 0; $i < 20000; $i++){
                $val = $redis->get('book');
//            }
            $s1 = new \DateTime();
            $s2 = $s1->format('s');

            return new JsonResponse(array(
                'book' => $val,
                'time' => $s2 - $d2
            ));
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

    /**
     * @Route("/recycle")
     */
    public function recycleAction(Request $request){
        if($request->isXmlHttpRequest()){
            return $this->render('EnterpriseBundle:Default:recycle.html.twig');
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

    /**
     * @Route("/search")
     */
    public function searchAction(Request $request){
        if($request->isXmlHttpRequest()){
//            $redis = $this->container->get('snc_redis.default');
//            $users = $redis->zrange('users', 0, -1);
//
//            foreach($users as $k=>$v){
//                $data[] = $redis->zscore('users', $v);
//            }

            return $this->render('EnterpriseBundle:Default:search.html.twig', array(
//                    'users' => $data
                ));
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

    /**
     * @Route("/settings")
     */
    public function settingsAction(Request $request){
        if($request->isXmlHttpRequest()){
            $str = 'http://www.inspiritcompany.ru/userfiles/shop_cat_images/btm009fle.jpg,http://www.inspiritcompany.ru/userfiles/shop_cat_images/btm009fle_1.jpg';
            $delimiter = strpos($str, ',');
            $res = substr($str, 0, $delimiter);
            return $this->render('EnterpriseBundle:Default:settings.html.twig', array('string' => $res));
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

}


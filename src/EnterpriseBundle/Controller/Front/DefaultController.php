<?php

namespace EnterpriseBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{

    /**
     * @Route("/")
     */
    public function indexAction(){

        $menu = $this->getDoctrine()->getRepository('EnterpriseBundle:Category')
            ->findBy(array('static' => true));

        return $this->render(':default:index.html.twig', array('menu' => $menu));

    }

    /**
     * @Route("/{path}", requirements={"path" = "[a-z\-]+"})
     */
    public function staticAction(Request $request){

        return $this->render(':default:'.$request->get('path').'.html.twig');
    }

}


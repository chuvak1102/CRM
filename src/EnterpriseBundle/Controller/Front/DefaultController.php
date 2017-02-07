<?php

namespace EnterpriseBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route
     * (
     *  "/{path}",
     *  requirements={"path" = "(?!login|register)[a-z0-9\-]+"}
     * )
     */
    public function staticAction(Request $request){

        if ($this->get('templating')->exists(':default:'.$request->get('path').'.html.twig') ) {
            return $this->render(':default:'.$request->get('path').'.html.twig');
        } else {
            return $this->render(':default:404.html.twig');
        }
    }

}


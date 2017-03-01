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

        $sRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Category');
        $rootCats = $sRepo->findBy(array(
            'lvl' => 0,
            'static' => 0
        ));

        return $this->render(':default:index.html.twig', array('catalog' => $rootCats));

    }


    /**
     * @Route
     * (
     *  "/{path}",
     *  requirements={"path" = "(?!login|register)[a-z0-9\-]+"}
     * )
     */
    public function staticAction(Request $request){

        $page = $request->get('path');
//        if($page == 'catalog'){
//            $page = 'category';
//        }

        if ($this->get('templating')->exists(':default:'.$page.'.html.twig') ) {
            $sRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Category');
            $static = $sRepo->findBy(array('title' => $page));
            $rootCats = $sRepo->findBy(array(
                'lvl' => 0,
                'static' => 0
            ));

            return $this->render(':default:'.$page.'.html.twig', array(
                'breadcrumbs' => $static,
                'category' => $rootCats,
                'products' => null
            ));
        } else {
            return $this->render(':default:404.html.twig');
        }
    }

}


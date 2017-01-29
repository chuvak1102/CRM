<?php

namespace EnterpriseBundle\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EnterpriseBundle\Entity\Category;

/**
 * @Route("/category")
 */
class CategoryController extends Controller
{

    /**
     *
     */
    public function createAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $sub = new Category();
        $sub->setTitle('sub');

        $sub1 = new Category();
        $sub1->setTitle('sub1');
        $sub1->setParent($sub);

        $sub2 = new Category();
        $sub2->setTitle('sub2');
        $sub2->setParent($sub1);

        $sub3 = new Category();
        $sub3->setTitle('sub3');
        $sub3->setParent($sub2);

        $em->persist($sub);
        $em->persist($sub1);
        $em->persist($sub2);
        $em->persist($sub3);

        $em->flush();

        return new Response('created');
    }

    /**
     * @Route("/{path}", requirements={"path" = "[a-z\/\-0-9]+"})
     */
    public function indexAction(Request $request)
    {
        $dir = '/var/www/job/app/Resources/views/default/';
        $file = 'static.html.twig';

        $r = $_SERVER['DOCUMENT_ROOT'];
        $site = str_replace('/var/www/', '', $r);

        if( !file_exists($dir.$file)) {
            $fp = fopen($dir.$file, "w");
            fwrite($fp, $request->get('path'));
            fclose ($fp);
        }

        return new Response($request->get('path'));
    }


}

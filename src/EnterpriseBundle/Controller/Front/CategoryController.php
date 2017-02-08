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
        $cRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Category');
        $pRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Catalog');

        $requestRoute = str_replace('/', '', $request->get('path'));
        $path = explode('/', $request->get('path'));

        $lastCatInRoute = $cRepo->findOneBy(array('title' => $path[count($path) - 1]));

        if(!$lastCatInRoute){
            $lastCatInRoute = $cRepo->findOneBy(array('title' => $path[count($path) - 2]));
            $allCatRoutes = $cRepo->getPath($lastCatInRoute);

            $product = $pRepo->findOneBy(array('alias' => $path[count($path) - 1]));
            if($product){
                $products = $product->getCategory()->getProducts();
                return $this->render(':default:product.html.twig', array(
                    'products' => $products,
                    'breadcrumbs' => $allCatRoutes
                ));
            } else {
                return $this->render(':default:404.html.twig');
            }

        }

        $allCatRoutes = $cRepo->getPath($lastCatInRoute);
        $route = '';
        foreach($allCatRoutes as $a){
            $route = $route.$a->getTitle();
        }

        if(strcmp($route, $requestRoute) === 0){

            $category = $cRepo->findBy(array('lvl' => $lastCatInRoute->getLvl() + 1, 'static' => 0));

            return $this->render(':default:category.html.twig', array(
                'products' => $lastCatInRoute->getProducts(),
                'category' => $category,
                'breadcrumbs' => $allCatRoutes
            ));
        } else {

            return $this->render(':default:404.html.twig');
        }

    }


}

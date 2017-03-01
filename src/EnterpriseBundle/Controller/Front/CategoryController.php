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
     * @Route("/{path}/page{num}/", requirements={"path" = "[a-z\/\-0-9]+", "num" = "[\d]+"})
     */
    public function paginateAction(Request $request){

        $count = 30;
        $page = intval($request->get('num'));
        $path = 'category/'.$request->get('path');
        $pathArray = explode('/', $request->get('path'));
        $start = $page * $count - $count;
        $end = $page * $count;

        $pCount = $this->getDoctrine()->getRepository('EnterpriseBundle:Catalog')
            ->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $pagesCount = ceil($pCount/$count);
        for($i = 1; $i <= $pagesCount; $i++){
            $pagination[] = $path.'/page'.$i.'/';
        }

        $cRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Category');
        $lastCatInRoute = $cRepo->findOneBy(array('title' => $pathArray[count($pathArray) - 1]));
        $allCatRoutes = $cRepo->getPath($lastCatInRoute);

        $products = $this->getDoctrine()->getRepository('EnterpriseBundle:Catalog')
            ->createQueryBuilder('p')
            ->setFirstResult($start)
            ->setMaxResults($end)
            ->getQuery()
            ->getResult();

        return $this->render(':default:pagination.html.twig', array(
            'products' => $products,
            'breadcrumbs' => $allCatRoutes,
            'pagination' => $pagination,
            'current' => $page
        ));
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

            if(!$lastCatInRoute){
                return $this->render(':default:404.html.twig');
            }

            $allCatRoutes = $cRepo->getPath($lastCatInRoute);

            $product = $pRepo->findOneBy(array('alias' => $path[count($path) - 1]));
            if($product){

                return $this->render(':default:product.html.twig', array(
                    'product' => $product,
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

            $cat = $cRepo->children($lastCatInRoute);
            if(!empty($cat)){
                foreach($cat as $c){
                    if($c->getLvl() === $lastCatInRoute->getLvl() + 1 && $c->getStatic() === false){
                        $category[] = $c;
                    }
                }

                $category = !empty($category) ? $category : null;

                return $this->render(':default:category.html.twig', array(
                    'products' => $lastCatInRoute->getProducts(),
                    'category' => $category,
                    'breadcrumbs' => $allCatRoutes
                ));
            } else {

                $products = $pRepo->findBy(array('category' => $lastCatInRoute->getId()));
                return $this->render(':default:products.html.twig', array(
                    'products' => $products,
                    'breadcrumbs' => $allCatRoutes
                ));
            }

        } else {

            return $this->render(':default:404.html.twig');
        }

    }


}

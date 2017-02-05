<?php

namespace EnterpriseBundle\Controller\Admin;

use References\Fixture\ORM\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use EnterpriseBundle\Entity\Category as StaticPage;

/**
 * @Route("admin/constructor")
 */
class ConstructorController extends Controller
{

    private function getCurrUser()
    {
        return $this->get('security.context')
            ->getToken()
            ->getUser();
    }

    /**
     * @Route("/")
     */
    public function indexAction(Request $request){

        if($request->isXmlHttpRequest()){

            $staticPages = $this->getDoctrine()->getRepository('EnterpriseBundle:Category')
                ->findBy(array(
                    'static' => true
                ));

            return $this->render('EnterpriseBundle:Default/constructor:index.html.twig',
                array(
                    'static' => $staticPages
                )
            );

        } else {

            throw new \Exception('ajax only!');
        }
    }

    /**
     * @Route("/create-static")
     */
    public function createStaticAction(Request $request){
        if($request->isXmlHttpRequest()){

        $constructor = $this->get('template_generator');
        $template = $constructor
            ->createStatic($request->get('template'), $request->get('html'));

        if($template->templateName){

            if(!$template->exist){
                $static = new StaticPage;
                $static->setTitle($request->get('template'));
                $static->setCanonical($request->get('name'));
                $static->setStatic(true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($static);
                $em->flush();
            }

            return $this->redirectToRoute('enterprise_admin_constructor_index');

        } else {
            return new JsonResponse(array(
                'created' => false
            ));
        }

        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }
}
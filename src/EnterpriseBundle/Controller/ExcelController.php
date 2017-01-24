<?php

namespace EnterpriseBundle\Controller;

use EnterpriseBundle\Entity\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use EnterpriseBundle\Entity\Notepad;

/**
 * Class MessagesController
 * @package EnterpriseBundle\Controller
 * @Route("/excel")
 */
class ExcelController extends Controller
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

            return $this->render('EnterpriseBundle:Default/excel:index.html.twig');
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }



}
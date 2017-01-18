<?php

namespace EnterpriseBundle\Controller;

use EnterpriseBundle\Entity\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use EnterpriseBundle\Entity\Users;
use EnterpriseBundle\Entity\DialogUsers;
use EnterpriseBundle\Entity\Dialog;
use EnterpriseBundle\Entity\Messages as Message;
use EnterpriseBundle\Entity\MessageImportant as ImportantMessage;
use EnterpriseBundle\Entity\MessageHidden as RemovedMessage;
/**
 * Class MessagesController
 * @package EnterpriseBundle\Controller
 * @Route("/calendar")
 */
class CalendarController extends Controller
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
            return $this->render('EnterpriseBundle:Default:calendar.html.twig');
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }
}
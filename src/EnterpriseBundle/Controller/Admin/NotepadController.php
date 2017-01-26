<?php

namespace EnterpriseBundle\Controller\Admin;

use EnterpriseBundle\Entity\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use EnterpriseBundle\Entity\Notepad;

/**
 * Class MessagesController
 * @package EnterpriseBundle\Controller\Admin
 * @Route("admin/notepad")
 */
class NotepadController extends Controller
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

            $nRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Notepad');
            $notes = $nRepo->findBy(array(
                'user_id' => $this->getCurrUser()->getId()
            ));

            $ids = '';
            $colors = '';
            foreach($notes as $note){
                $ids = $ids.':'.$note->getId();
                $colors = $colors.':'.$note->getColor();
            }

            if(empty($ids)){
                $ids = null;
                $colors = null;
            }

            return $this->render('EnterpriseBundle:Default:notepad.html.twig',
                array(
                    'notes' => $notes,
                    'ids' => $ids,
                    'colors' => $colors
                ));
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

    /**
     * @Route("/add")
     */
    public function addAction(Request $request){
        if($request->isXmlHttpRequest()){

            $notepad = new Notepad;
            $notepad->setUserId($this->getCurrUser()->getId());
            $notepad->setHeader($request->get('header'));
            $notepad->setMessage($request->get('text'));
            $notepad->setColor($request->get('color'));
            $notepad->setCreated(new \DateTime);
            $notepad->setHidden(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($notepad);
            $em->flush();

            return $this->redirectToRoute('enterprise_admin_notepad_index');
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

    /**
     * @Route("/edit/{id}", requirements={"id":"[\d]+"})
     */
    public function editAction(Notepad $notepad, Request $request){
        if($request->isXmlHttpRequest()){

            $c = explode('_', $request->get('color'));
            $color = $c[1];

            $notepad->setHeader($request->get('header'));
            $notepad->setMessage($request->get('text'));
            $notepad->setColor($color);

            $em = $this->getDoctrine()->getManager();
            $em->persist($notepad);
            $em->flush();

            return $this->redirectToRoute('enterprise_admin_notepad_index');
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

    /**
     * @Route("/close/{id}", requirements={"id":"[\d]+"})
     */
    public function closeAction(Notepad $notepad, Request $request){
        if($request->isXmlHttpRequest()){

            $em = $this->getDoctrine()->getManager();
            $em->remove($notepad);
            $em->flush();

            return new JsonResponse(array());
        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }


}
<?php
namespace EnterpriseBundle\Controller;

use EnterpriseBundle\Entity\Messages;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use EnterpriseBundle\Entity\Users;
use EnterpriseBundle\Entity\DialogLink;
use EnterpriseBundle\Entity\Dialogs as Dialog;
use EnterpriseBundle\Entity\Messages as Message;
/**
 * Class MessagesController
 * @package EnterpriseBundle\Controller
 * @Route("/messages")
 */
class MessagesController extends Controller {

    private function getCurrUser(){
        return $this->get('security.context')
            ->getToken()
            ->getUser();
    }

    private function findPair($from, $to){
        $redis = $this->container->get('snc_redis.default');
        $variantA = $from.'-'.$to;
        $variantB = $to.'-'.$from;

        if($redis->sismember('pair', $variantA)){
            return $variantA;
        } else if ($redis->sismember('pair', $variantB)){
            return $variantB;
        } else {
            return false;
        }
    }

    /**
     * @Route("/")
     */
    public function indexAction(Request $request){

        if($request->isXmlHttpRequest()){

            $dRepo = $this->getDoctrine()
                ->getRepository('EnterpriseBundle:DialogLink');
            $mRepo = $this->getDoctrine()
                ->getRepository('EnterpriseBundle:Dialogs');

            $dialogs = $dRepo->findBy(array(
                'user_id' => $this->getCurrUser()->getId()
            ));

            if(!empty($dialogs)){
                foreach($dialogs as $dialog){
                    if($dialog->isHidden() == false){
                        $messages[] = $mRepo->findBy(array(
                            'dialog_alias' => $dialog->getDialogAlias()
                        ));
                    }
                }
            }

            if(empty($messages)){
                $messages = null;
            }

            return $this->render('EnterpriseBundle:Default/messages:index.html.twig', array(
                'dialogs' => $messages,
                'lastdialog' => $this->getCurrUser()->getLastDialog()
            ));

        } else {
            throw new \Exception('Ajax only!');
        }
    }

    /**
     * @Route("/search")
     */
    public function searchAction(Request $request){
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $pRepo = $em->getRepository('EnterpriseBundle:Users');

            $query = $pRepo->createQueryBuilder('u')
                ->where('u.fullname LIKE :fullname')
                ->setParameter('fullname', '%'.$request->get('name').'%')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();

            if(!empty($query)){
                foreach($query as $someone){
                    if($someone->getId() != $this->getCurrUser()->getId()){
                        $name[] = $someone->getFullname();
                        $id[] = $someone->getId();
                    }
                }
            } else {
                $name = null;
                $id = null;
            }

            return new JsonResponse(array(
                'name' => $name,
                'id' => $id
            ));
        } else {
            throw new \Exception('Ajax only!');
        }
    }

    /**
     * @Route("/allusers")
     */
    public function allUsersAction(Request $request){
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $uRepo = $em->getRepository('EnterpriseBundle:Users');

            $query = $uRepo->findAll();

            if(!empty($query)){
                foreach($query as $someone){
                    if($someone->getId() != $this->getCurrUser()->getId()){
                        $name[] = $someone->getFullname();
                        $id[] = $someone->getId();
                    }
                }
            } else {
                $name = null;
                $id = null;
            }

            return new JsonResponse(array(
                'name' => $name,
                'id' => $id
            ));
        } else {
            throw new \Exception('Ajax only!');
        }
    }

    /**
     * @Route("/newmessage/{dialog}", requirements={"dialog":"[\d]+"})
     */
    public function newMessageAction(Dialog $dialog, Request $request){

        if($request->isXmlHttpRequest()){

            $message = new Message();
            $message->setDialogAlias($dialog);
            $message->setMessage($request->get('message'));
            $message->setCreated(new \DateTime());
            $message->setAuthorId($this->getCurrUser()->getId());
            $message->setAuthorName($this->getCurrUser()->getFullname());

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return new JsonResponse(array('ok' => true));

        } else {
            throw new \Exception('Ajax only!');
        }
    }

    /**
     * @Route("/newchat")
     */
    public function beginChatAction(Request $request){

        if($request->isXmlHttpRequest()){

            if(!empty($request->get('users'))){
                $u = $request->get('users');
                $dialog_name = $request->get('dialog_name');
                array_push($u, $this->getCurrUser()->getId());
                sort($u);
                $dialogAlias = implode(':', $u);
                $usersCount = count($u);
                $em = $this->getDoctrine()->getManager();

                $dRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Dialogs');
                $exist = $dRepo->findOneBy(array(
                    'dialog_alias' => $dialogAlias
                ));

                if(!empty($exist)){
                    $lRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:DialogLink');
                    $link = $lRepo->findOneBy(array(
                        'dialog_alias' => $dialogAlias,
                        'user_id' => $this->getCurrUser()->getId()
                    ));
                    $link->setHidden(false);
                    $em->persist($link);
                    $em->flush();
                    return new JsonResponse(array('exist' =>
                        array(
                            'id' => $exist->getId(),
                            'name' => $exist->getDialogName()
                        )));
                } else {

                    foreach($u as $user){
                        $dialogAl = new DialogLink();
                        $dialogAl->setUserId($user);
                        $dialogAl->setDialogAlias($dialogAlias);
                        $em->persist($dialogAl);
                    }

                    $dialog = new Dialog();
                    $dialog->setDialogAlias($dialogAlias);
                    $dialog->setCreated(new \DateTime());

                    if(!empty($dialog_name)){
                        $dialog->setDialogName($dialog_name);
                    } else {
                        $dialog->setDialogName($usersCount.' участника(ов)');
                    }

                    $em->persist($dialog);
                    $em->flush();

                    return new JsonResponse(array(
                        'new' => array(
                            'id' => $dialog->getId(),
                            'name' => $dialog->getDialogName()
                        )
                    ));
                }

            } else {
                return new JsonResponse(array('dialog' => false));
            }

        } else {
            throw new Exception('Ajax only!');
        }
    }

    /**
     * @Route("/update/{dialog}", requirements={"dialog":"[\d]+"})
     */
    public function updateMessagesAction(Dialog $dialog, Request $request){
        if($request->isXmlHttpRequest()){
            $user = $this->getCurrUser();
            $user->setLastDialog($dialog->getId());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->render('EnterpriseBundle:Default/messages:incoming.html.twig',
                array(
                    'messages' => $dialog->getMessages()
                )
            );

        } else {
            throw new \Exception('ajax only');
        }
    }

    /**
     * @Route("/lastdialog")
     */
    public function getLastDialogAction(Request $request){
        if($request->isXmlHttpRequest()){

            if($this->getCurrUser()->getLastDialog()){
                $uRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Users');
                $dRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Dialogs');
                $dialogAlias = $dRepo->getUsersInDialog($this->getCurrUser()
                    ->getLastDialog())[0]->getDialogAlias();

                $users = explode(':', $dialogAlias);

                foreach($users as $user){
                    $name = $uRepo->findOneBy(array(
                        'id' => $user
                    ));
                    $names[] = $name->getFullname();
                    $ids[] = $name->getId();
                }

                return new JsonResponse(array(
                    'data' => array(
                        'dialog' => $this->getCurrUser()->getLastDialog(),
                        'users' => $names,
                        'ids' => $ids
                    )
                ));
            } else {
                return new JsonResponse(array(
                    'empty' => true
                ));
            }

        } else {
            throw new \Exception('ajax only');
        }
    }

    /**
     * @Route("/hide/{dialog}", requirements={"dialog":"[\d]+"})
     */
    public function hideDialogAction(Dialog $dialog, Request $request){
        if($request->isXmlHttpRequest()){
            $user = $this->getCurrUser()->getId();
            $alias = $dialog->getDialogAlias();

            $linkedDialog = $this->getDoctrine()
                ->getRepository('EnterpriseBundle:DialogLink')
                ->findOneBy(array(
                    'dialog_alias' => $alias,
                    'user_id' => $user
                ));

            $linkedDialog->setHidden(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($linkedDialog);
            $em->flush();

            return new JsonResponse(array('hidden' => true));

        } else {
            throw new \Exception('ajax only');
        }
    }

    /**
     * @Route("/removemessages")
     */
    public function removeMessagesAction(Request $request){
        if($request->isXmlHttpRequest()){

            $mRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Messages');
            $user = $this->getCurrUser()->getUsername();
            $ids = $request->get('messages');

            $em = $this->getDoctrine()->getManager();
            foreach($ids as $id){
                $message = $mRepo->findOneBy(array('id' => $id));
                if(!empty($message)){
                    $hidden = $this->addToString($message->getHidden(), $user);
                    $message->setHidden($hidden);
                    $em->persist($message);
                }
            }

            $em->flush();

            return new JsonResponse(array('removed' => true));

        } else {
            throw new \Exception('ajax only');
        }
    }

    /**
     * @Route("/important")
     */
    public function markAsImportantAction(Request $request){
        if($request->isXmlHttpRequest()){

            $mRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Messages');
            $user = $this->getCurrUser()->getUsername();
            $ids = $request->get('messages');
            $em = $this->getDoctrine()->getManager();

            foreach($ids as $id){
                $message = $mRepo->findOneBy(array('id' => $id));
                if(!empty($message)){
                    $importantFor = $message->getImportant();
                    $important = explode(':', $importantFor);

                    if(in_array($user, $important)){
                        $important = $this->removeFromString($importantFor, $user);
                    } else {
                        $important = $this->addToString($importantFor, $user);
                    }

                    $message->setImportant($important);
                    $em->persist($message);
                }
            }

            $em->flush();

            return new JsonResponse(array('marked' => true));

        } else {
            throw new \Exception('ajax only');
        }
    }

    /**
     * @Route("/removeuser/{user}/{dialog}", requirements={"dialog":"[\d]+", "user":"[\d]+"})
     */
    public function removeFromDialogAction(Users $user, Dialog $dialog, Request $request){

        $em = $this->getDoctrine()->getManager();
        $dRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:DialogLink');
        $dLinks = $dRepo->findBy(array(
            'dialog_alias' => $dialog->getDialogAlias(),
        ));

        if(count($dLinks) > 2 && $this->getCurrUser()->getId() != $user->getId()){
            foreach($dLinks as $link){
                if($link->getUserId() == $user->getId()){
                    $em->remove($link);
                } else {
                    $newAlias = $this->removeFromString($link->getDialogAlias(), $user->getId());
                    $link->setDialogAlias($newAlias);
                    $em->persist($link);
                }
            }

            $newAlias = $this->removeFromString($dialog->getDialogAlias(), $user->getId());
            $dialog->setDialogAlias($newAlias);
            $em->persist($dialog);
            $em->flush();

            return new JsonResponse(array('success' => true));

        } else {
            return new JsonResponse(array('error' => 'Невозможно удалить самого себя, либо в диалоге осталось менее трех челвоек!'));
        }
    }

    /**
     * @Route("/addtodialog/{dialog}", requirements={"dialog":"[\d]+"})
     */
    public function addToDialogAction(Dialog $dialog, Request $request){

        $x = 0;

        $mHelper = $this->get('enterprise.mhelper');

        $dialog = $mHelper->removeFromString('1:2:3:4', '2');

        return new JsonResponse(array('ok' => $dialog));
    }

    public function addToString($string, $element){
        $array = explode(':', $string);
        array_push($array, $element);
        $array = implode(':', $array);
        return $array;
    }

    public function removeFromString($string, $element){
        if($string && $element){
            $alias = explode(':', $string);
            unset($alias[array_search($element, $alias)]);

            return implode(':', $alias);
        } else {
            return false;
        }
    }

    private function getUersFromDialogAlias($stringAlias){
        $uRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Users');
        $array = explode(':', $stringAlias);
        foreach($array as $user){
            $users[] = $uRepo->findOneBy(array(
                'id' => $user
            ));
        }

        return !empty($users) ? $users : false;
    }

}
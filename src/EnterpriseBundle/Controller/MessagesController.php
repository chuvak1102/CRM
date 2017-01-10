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

//        if($request->isXmlHttpRequest()){

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
                'dialogs' => $messages
            ));

//        } else {
//            throw new \Exception('Ajax only!');
//        }
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

//    /**
//     * @Route("/newchat")
//     */
//    public function beginChatAction(Request $request){
//
//        if($request->isXmlHttpRequest()){
//
//            $userId = $request->get('user');
//            $companionId = $request->get('companion');
//            $redis = $this->container->get('snc_redis.default');
//
//            if($this->checkUser($userId)){
//                $dialogId = $this->findPair($userId, $companionId);
//
//                if($redis->sismember('pair', $dialogId)){
//                    $messages = $this->getMessagesByDialog($dialogId);
//                    if(!empty($messages)){
//                        return $this->render('EnterpriseBundle:Default/messages:incoming.html.twig',
//                            array(
//                                'messages' => $messages
//                            ));
//                    } else {
//                        return new JsonResponse(array(
//                            'empty'=>true
//                        ));
//                    }
//
//                } else {
//                    $redis->sadd('pair', $userId.'-'.$companionId);
//                    $redis->rpush('messages'.':'.$userId.'-'.$companionId, '');
//                }
//
//            } else {
//                return new JsonResponse(array(
//                    'ok'=>'Can not send message.'
//                ));
//            }
//
//            return new JsonResponse(array(
//                'ok'=>true
//            ));
//
//        } else {
//            throw new Exception('ajax only');
//        }
//    }

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
                return new JsonResponse(array(
                    'dialog' => $this->getCurrUser()->getLastDialog()
                ));
            } else {
                return new JsonResponse(array(
                    'dialog' => false
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
                    $hiddenBy = $message->getHidden();
                    $hidden = explode(':', $hiddenBy);
                    array_push($hidden, $user);
                    $newHidden = implode(':', $hidden);
                    $message->setHidden($newHidden);
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
                        $key = array_search($user, $important);
                        unset($important[$key]);
                        $newImportant = implode(':', $important);
                        $message->setImportant($newImportant);
                        $em->persist($message);
                    } else {
                        array_push($important, $user);
                        $newImportant = implode(':', $important);
                        $message->setImportant($newImportant);
                        $em->persist($message);
                    }
                }
            }

            $em->flush();

            return new JsonResponse(array('marked' => true));

        } else {
            throw new \Exception('ajax only');
        }
    }



}
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

            $dRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:DialogUsers');
            $messages = $dRepo->getMessagesInLastDialog($this->getCurrUser()->getLastDialog());
            $users = $dRepo->getUsersInLastDialog($this->getCurrUser()->getLastDialog());
            $dialogs = $dRepo->getDialogsByUser($this->getCurrUser()->getId());

            if (!empty($messages) ? $messages : $messages = null);
            if (!empty($dialogs) ? $dialogs : $dialogs = null);
            if (!empty($users) ? $users : $users = null);

            return $this->render('EnterpriseBundle:Default/messages:index.html.twig', array(
                'dialogs' => $dialogs,
                'messages' => $messages,
                'users' => $users,
                'lastdialog' => $this->getCurrUser()->getLastDialog(),
                'currUser' => $this->getCurrUser()->getId()
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
            $message->setDialog($dialog);
            $message->setMessage($request->get('message'));
            $message->setCreated(new \DateTime());
            $message->setAuthor($this->getCurrUser()->getId());
            $message->setAuthorName($this->getCurrUser()->getFullname());

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return new JsonResponse(array('ok' => 'ok'));

//            return $this->render('EnterpriseBundle:Default/messages:incoming.html.twig',
//                array(
//                    'messages' => $dialog->getMessages()
//                )
//            );

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

                $users = $request->get('users');
                $currUser = $this->getCurrUser();
                array_push($users, $currUser->getId());

                $dialog_name = $request->get('dialog_name');
                if(empty($dialog_name)){
                    $dialog_name = count($users).' участника(ов)';
                }
                $em = $this->getDoctrine()->getManager();
                $date = new \DateTime();

                $dialog = new Dialog();
                $dialog->setCreated($date);
                $dialog->setDialogName($dialog_name);
                $dialog->setCreator($this->getCurrUser()->getId());

                $em->persist($dialog);

                $em->flush();

                // переписать это говно!!!
                $dialogId = $this->getDoctrine()
                    ->getManager()
                    ->createQuery('SELECT e FROM EnterpriseBundle:Dialog e WHERE e.created = :created')
                    ->setParameter('created', $date)
                    ->getResult()[0]->getId();

                $currUser->setLastDialog($dialog->getId());
                $em->persist($currUser);
                // конец говна

                foreach($users as $user){
                    $dialogUsers = new DialogUsers();
                    $dialogUsers->setDialog($dialogId);
                    $dialogUsers->setUserId($user);
                    $em->persist($dialogUsers);
                }

                $em->flush();

                return $this->redirectToRoute('enterprise_messages_index');

            } else {
                return new JsonResponse(array('created' => false));
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

            $em = $this->getDoctrine()->getManager();
            $user = $this->getCurrUser()->setLastDialog($dialog->getId());
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('enterprise_messages_index');
        } else {
            throw new \Exception('ajax only');
        }
    }

    /**
     * @Route("/hide/{dialog}", requirements={"dialog":"[\d]+"})
     */
    public function hideDialogAction(Dialog $dialog, Request $request){
        if($request->isXmlHttpRequest()){

            $dRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:DialogUsers');
            $hiddenDialog = $dRepo->findOneBy(array(
                'dialog' => $dialog->getId(),
                'user_id' => $this->getCurrUser()->getId()
            ));

            $em = $this->getDoctrine()->getManager();
            $user = $this->getCurrUser();
            $user->setLastDialog(null);
            $em->persist($user);
            $em->remove($hiddenDialog);
            $em->flush();

            return $this->redirectToRoute('enterprise_messages_index');
        } else {
            throw new \Exception('ajax only');
        }
    }

    /**
     * @Route("/removemessages")
     */
    public function removeMessagesAction(Request $request){
        if($request->isXmlHttpRequest()){

            $ids = $request->get('messages');
            $em = $this->getDoctrine()->getManager();

            foreach($ids as $id){
                $removed = new RemovedMessage;
                $removed->setMessageId($id);
                $removed->setHiddenBy($this->getCurrUser()->getId());
                $em->persist($removed);
            }

            $em->flush();

            return $this->redirectToRoute('enterprise_messages_index');

        } else {
            throw new \Exception('ajax only');
        }
    }

    /**
     * @Route("/important")
     */
    public function markAsImportantAction(Request $request){
        if($request->isXmlHttpRequest()){

            $iRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:MessageImportant');
            $ids = $request->get('messages');
            $em = $this->getDoctrine()->getManager();

            foreach($ids as $id){
                $exist = $iRepo->findOneBy(array(
                    'message' => $id,
                    'importantFor' => $this->getCurrUser()->getId()
                ));
                if(empty($exist)){
                    $important = new ImportantMessage;
                    $important->setMessage($id);
                    $important->setImportantFor($this->getCurrUser()->getId());
                    $em->persist($important);
                } else {
                    $em->remove($exist);
                }

            }

            $em->flush();

            return $this->redirectToRoute('enterprise_messages_index');

        } else {
            throw new \Exception('ajax only');
        }
    }

    /**
     * @Route("/removeuser/{user}/{dialog}", requirements={"dialog":"[\d]+", "user":"[\d]+"})
     */
    public function removeFromDialogAction(Users $user, Dialog $dialog, Request $request){

        if($request->isXmlHttpRequest()){

        $dRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:DialogUsers');
        $hiddenDialog = $dRepo->findOneBy(array(
            'dialog' => $dialog->getId(),
            'user_id' => $user->getId()
        ));

        $em = $this->getDoctrine()->getManager();
            if($user->getId() == $this->getCurrUser()->getId()){
                $currUser = $this->getCurrUser();
                $currUser->setLastDialog(null);
                $em->persist($currUser);
            }
        $em->remove($hiddenDialog);
        $em->flush();

        return $this->redirectToRoute('enterprise_messages_index');

        } else {
            throw new \Exception('ajax only');
        }
    }

    /**
     * @Route("/invite/{user}/{dialog}", requirements={"dialog":"[\d]+", "user":"[\d]+"})
     */
    public function addToDialogAction(Users $user, Dialog $dialog, Request $request){

        if($request->isXmlHttpRequest()){

            $dRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:DialogUsers');
            $exist = $dRepo->findOneBy(array(
                'dialog' => $dialog->getId(),
                'user_id' => $user->getId()
            ));

            if(empty($exist)){
                $dialoguser = new DialogUsers();
                $dialoguser->setUserId($user->getId());
                $dialoguser->setDialog($dialog->getId());

                $em = $this->getDoctrine()->getManager();
                $em->persist($dialoguser);
                $em->flush();
            }

            return $this->redirectToRoute('enterprise_messages_index');

        } else {
            throw new \Exception('ajax only');
        }
    }



    /**
     * @Route("/fileupload")
     */
    public function uploadAction(Request $request){

        // переписать это говно!!!
        $message = $this->getDoctrine()
            ->getManager()
            ->createQuery('SELECT e FROM EnterpriseBundle:Messages e WHERE e.author = :author ORDER BY e.id DESC')
            ->setParameter('author', $this->getCurrUser()->getId())
            ->setMaxResults(1)
            ->getResult()[0];

        $file = $_FILES['file'];
        $uploader = $this->get('file_uploader');
        $fileName = $uploader->save($file);

        $message->setFile($fileName);

        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        return $this->redirectToRoute('enterprise_messages_index');
    }

}
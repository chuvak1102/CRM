<?php

namespace EnterpriseBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use EnterpriseBundle\Entity\Seller;
use EnterpriseBundle\Entity\SellerProduct;
use EnterpriseBundle\Entity\SellerSettings;
use PHPExcel_IOFactory;
use PHPExcel_Cell;

/**
 * Class MessagesController
 * @package EnterpriseBundle\Controller\Admin
 * @Route("admin/documents")
 */
class DocumentsController extends Controller
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

            $sellers = $this->getDoctrine()->getRepository("EnterpriseBundle:Seller")
                ->getSellers();

            return $this->render("EnterpriseBundle:Default:documents.html.twig", array(
                'sellers' => $sellers
            ));

        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

    /**
     * @Route("/save-setting")
     */
    function saveSettingsAction(Request $request){

        $settings = new SellerSettings;

        if(!empty($request->get('id')))
            $settings->setSellerId($request->get('id'));
        if(!empty($request->get('VendorCode')))
            $settings->setVendorCode($request->get('VendorCode'));
        if(!empty($request->get('Name')))
            $settings->setName($request->get('Name'));
        if(!empty($request->get('Category')))
            $settings->setCategory($request->get('Category'));
        if(!empty($request->get('Price')))
            $settings->setPrice($request->get('Price'));
        if(!empty($request->get('Description')))
            $settings->setDescription($request->get('Description'));
        if(!empty($request->get('shortDescription')))
            $settings->setShortDescription($request->get('shortDescription'));
        if(!empty($request->get('Image')))
            $settings->setImage($request->get('Image'));

        if(!empty($request->get('Properties')))
        foreach($request->get('Properties') as $columnNum){
            $set[] = $columnNum;
        }

        if(!empty($set)){
            $settings->setSettings($set);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($settings);
        $em->flush();

        return new JsonResponse(array('success' => true));
    }

    /**
     * @Route("/excelprepare")
     */
    function prepareForParsingAction(Request $request){

        if($request->isXmlHttpRequest()){
        $fileLoader = $this->get('file_uploader');
        $file = $_FILES['file'];
        $name = $fileLoader->save($file);
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $phpExcelObject = $objReader->load($name);

        $letterColumn = $phpExcelObject->setActiveSheetIndex(0)->getHighestColumn();
        $integerColumn = PHPExcel_Cell::columnIndexFromString($letterColumn);

        for($i = 0; $i < $integerColumn; $i++){
            $fields[] = $i.$phpExcelObject
                ->setActiveSheetIndex(0)
                ->getCellByColumnAndRow($i,1)
                ->getValue();
        }

        if(!empty($fields)){
            return new JsonResponse(array('fields' => $fields));
        } else {
            return new JsonResponse(array('fields' => null));
        }

        } else {
            throw new \Exception('notForYouAction');
        }
    }

    /**
     * @Route("/parse/{id}", requirements={"id":"[\d]+"})
     */
    function parseAction(Seller $seller, Request $request){

        $settings = $this->getDoctrine()->getRepository('EnterpriseBundle:SellerSettings')
            ->findOneBy(array(
                'seller_id' => $seller->getId()
            ));



        $fileLoader = $this->get('file_uploader');
        $file = $_FILES['file'];
        $name = $fileLoader->save($file);
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $phpExcelObject = $objReader->load($name);


        $end = $phpExcelObject->setActiveSheetIndex(0)->getHighestRow();
        $em = $this->getDoctrine()->getManager();
        for($i = 2; $i <= $end; $i++){

            $category = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow($settings->getCategory(),$i)->getValue();
//            $vendor = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow(3,$i)->getValue();
            $name = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow($settings->getName(),$i)->getValue();
            $price = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow($settings->getPrice(),$i)->getValue();
            $description = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow($settings->getDescription(),$i)->getValue();
            $shortDescription = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow($settings->getShortDescription(),$i)->getValue();
            $image = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow($settings->getImage(),$i)->getValue();

            foreach($settings->getSettings() as $set){
                $otherSettings[] = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow($set,$i)->getValue();
            }

            $product = new SellerProduct;
            $product->setSeller($seller);
            $product->setCategory($category);
            $product->setName($name);
            $product->setPrice($price);
            $product->setDescription($description);
            $product->setShortDescription($shortDescription);
            $product->setImage($image);
            $product->setSettings($otherSettings);

            $em->persist($product);
            $em->flush();
        }

        return new JsonResponse(array('created' => 'ok'));
    }





}
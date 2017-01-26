<?php

namespace EnterpriseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use EnterpriseBundle\Entity\Seller;
use EnterpriseBundle\Entity\Product;
use PHPExcel_IOFactory;
use PHPExcel_Cell;

/**
 * Class MessagesController
 * @package EnterpriseBundle\Controller
 * @Route("/documents")
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

            $product = new Product;
            $product->setQuantity('Quantity');
            $product->setName('Name');
            $product->setPrice('Price');
            $product->setDescription('Description');
            $product->setCatalog('Catalog');
            $product->setCatalogCode('CatalogCode');
            $product->setCode('Code');
            $product->setCountry('Country');
            $product->setCustomPrice('CustomPrice');
            $product->setCustomPrice2('CustomPrice2');
            $product->setDiscount('Discount');
            $product->setImage('Image');
            $product->setSellerPrice('SellerPrice');
            $product->setItemPage('ItemPage');
            $product->setType('Type');
            $product->setVendorCode('VendorCode');

            return $this->render("EnterpriseBundle:Default:documents.html.twig",
                array('product' => $product));

        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

    /**
     * @Route("/excelprepare")
     */
    function prepareForParsingAction(Request $request){

//        if($request->isXmlHttpRequest()){
        $fileLoader = $this->get('file_uploader');
        $file = $_FILES['file'];
        $name = $fileLoader->save($file);
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $phpExcelObject = $objReader->load($name);

        $letterColumn = $phpExcelObject->setActiveSheetIndex(0)->getHighestColumn();
        $integerColumn = PHPExcel_Cell::columnIndexFromString($letterColumn);

        for($i = 0; $i < $integerColumn; $i++){
            $fields[] = $phpExcelObject
                ->setActiveSheetIndex(0)
                ->getCellByColumnAndRow($i,1)
                ->getValue();
        }

        if(!empty($fields)){
            return new JsonResponse(array('fields' => $fields));
        } else {
            return new JsonResponse(array('fields' => null));
        }

//        } else {
//            throw new \Exception('notForYouAction');
//        }
    }

    /**
     * @Route("/parse/{id}", requirements={"id":"[\d]+"})
     */
    function parseAction(Seller $seller, Request $request){

        $fileLoader = $this->get('file_uploader');
        $file = $_FILES['file'];
        $name = $fileLoader->save($file);
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $phpExcelObject = $objReader->load($name);


        $end = $phpExcelObject->setActiveSheetIndex(0)->getHighestRow();
        $em = $this->getDoctrine()->getManager();
        for($i = 0; $i < $end; $i++){
            $code = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow(1,$i);
            $vendor = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow(3,$i);
            $name = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow(4,$i);
            $description = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow(5,$i);
            $price = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow(6,$i);
            $quantity = $phpExcelObject->setActiveSheetIndex(0)->getCellByColumnAndRow(7,$i);

            $product = new Product;
            $product->setSeller($seller);
            $product->setCode($code);
            $product->setVendorCode($vendor);
            $product->setName($name);
            $product->setDescription($description);
            $product->setPrice($price);
            $product->setQuantity($quantity);

            $em->persist($product);
            $em->flush();
            unset($product);
        }

        return new JsonResponse(array('name' => $name));
    }





}
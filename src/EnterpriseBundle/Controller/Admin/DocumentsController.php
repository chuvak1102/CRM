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
use EnterpriseBundle\Entity\Catalog;
use EnterpriseBundle\Entity\Category;
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

            $set = $this->getDoctrine()->getRepository('EnterpriseBundle:SellerProduct')
                ->findOneBy(array(
                    'id' => 5
                ));

            return $this->render("EnterpriseBundle:Default:documents.html.twig", array(
                'sellers' => $sellers,
                'set' => $set
            ));

        } else {
            throw new \Exception('Get the fuck out of here...');
        }
    }

    /**
     * @Route("/catalogprepare")
     */
    function sitePriceSettingsAction(Request $request){

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
            throw new \Exception('ajax');
        }
    }

    /**
     * @Route("/save-settings")
     */
    function savePriceSettingsAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $exist = $this->getDoctrine()->getRepository('EnterpriseBundle:SellerSettings')
            ->findOneBy(array('seller_id' => $request->get('id')));

        if($exist)
        $em->remove($exist);

        $settings = new SellerSettings;
        $settings->setSellerId($request->get('id'));
        $settings->setVendorCode($request->get('VendorCode'));
        $settings->setName($request->get('Name'));
        $settings->setCategory($request->get('Category'));
        $settings->setCategoryName($request->get('CategoryName'));
        $settings->setPrice($request->get('Price'));
        $settings->setDescription($request->get('Description'));
        $settings->setShortDescription($request->get('ShortDescription'));
        $settings->setImage($request->get('Image'));

        if(!empty($request->get('Properties'))){
            foreach($request->get('Properties') as $columnNum){
                $set[] = $columnNum;
            }
        }

        if(!empty($set)){
            $settings->setAdvanced($set);
        }


        $em->persist($settings);
        $em->flush();

        return new JsonResponse(array('success' => true));
    }

    /**
     * @Route("/excelprepare")
     */
    function preparePriceSettingsAction(Request $request){

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
            throw new \Exception('ajax');
        }
    }


    /**
     * @Route("/parsecsv/{id}", requirements={"id":"[\d]+"})
     */
    function parseCSVAction(Seller $seller, Request $request){

        $file = $this->get('file_uploader')->save($_FILES['file']);
        $helpers = $this->get('helpers');
        $helpers->toUTF8($file);

        $fields = $this->getDoctrine()->getRepository('EnterpriseBundle:SellerSettings')
            ->findOneBy(array(
                'seller_id' => $seller->getId()
            ));

        $em = $this->getDoctrine()->getManager();

        $category = $fields->getCategory();
        $itemName = $fields->getName();
        $price = $fields->getPrice();
        $description = $fields->getDescription();
        $shortDescription = $fields->getShortDescription();
        $image = $fields->getImage();

        $csvFile = fopen($file, "r");
        while (($line = fgetcsv($csvFile, 0, ";")) !== false) {

            foreach($fields->getAdvanced() as $cell){
                $advanced[] = $line[$cell];
            }


            $product = new SellerProduct;
            $product->setSeller($seller);
            $product->setCategory($line[$category]);
            $product->setName($line[$itemName]);
            $product->setPrice($line[$price]);
            $product->setDescription($line[$description]);
            $product->setShortDescription($line[$shortDescription]);
            $product->setImage($line[$image]);
            $product->setAdvanced($advanced);

            $em->persist($product);

            unset($product);
            unset($advanced);
            unset($line);
        }
        $em->flush();
        unset($em);

        fclose($csvFile);
        unset($csvFile);
        return new JsonResponse(array('created' => 'ok'));
    }


    /**
     * @Route("/addtoshop")
     */
    function addToShopAction(){

        $file = $this->get('file_uploader')->save($_FILES['file']);
        $helpers = $this->get('helpers');
        $helpers->toUTF8($file);

        $fields = $this->getDoctrine()->getRepository('EnterpriseBundle:SellerSettings')
            ->findOneBy(array(
                'seller_id' => 1000
            ));

        $category = $fields->getCategory();
        $categoryName = $fields->getCategoryName();
        $vendor = $fields->getVendorCode();
        $itemName = $fields->getName();
        $price = $fields->getPrice();
        $description = $fields->getDescription();
        $shortDescription = $fields->getShortDescription();
        $image = $fields->getImage();

        $em = $this->getDoctrine()->getManager();

        $csvFile = fopen($file, "r");
        while (($line = fgetcsv($csvFile, 0, ";")) !== false) {

            foreach($fields->getAdvanced() as $cell){
                $advanced[] = $line[$cell];
            }

            $product = new Catalog();
            $product->setCategory(
                $this->createCategories(
                    $line[$category],
                    $line[$categoryName],
                    $line[$description],
                    $line[$image]
                ));
            $product->setCategoryName($line[$categoryName]);
            $product->setVendorCode($line[$vendor]);
            $product->setName($line[$itemName]);
            $product->setAlias($helpers->stringToUrl($line[$itemName]));
            $product->setPrice($line[$price]);
            $product->setDescription($line[$description]);
            $product->setShortDescription($line[$shortDescription]);
            $product->setImage($line[$image]);
            $product->setAdvanced($advanced);

            $em->persist($product);

            unset($product);
            unset($advanced);
            unset($line);
        }
        $em->flush();
        unset($em);

        fclose($csvFile);
        unset($csvFile);
        return new JsonResponse(array('created' => 'ok'));
    }

    function createCategories($categoryPath, $categoryName, $description, $image){

        $cRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Category');
        $em = $this->getDoctrine()->getManager();
        $catList = explode('/', $categoryPath);
        $name = array();

        for($i = 0; $i < count($catList); $i++){
            $name[$i] = '';
            if($i == count($catList) - 1){
                $name[$i] = $categoryName;
                $catName = $name[$i];
                break;
            }
        }

        for($i = 0; $i < count($catList); $i++){

            $existCat = $cRepo->findOneBy(array('title' => $catList[$i]));
            if(!$existCat){

                $category = new Category;
                $category->setTitle($catList[$i]);
                if($i == count($catList) - 1){
                    $category->setCanonical($catName);
                    $category->setDescription($description);
                    $category->setImage($image);
                }
                $em->persist($category);
                $em->flush();

                if($i > 0){
                    $parent = $cRepo->findOneBy(array('title' => $catList[$i-1]));
                    if($parent){
                        $category->setParent($parent);
                        $em->persist($category);
                        $em->flush();
                    }
                }

            } else {

                if($i > 0){
                    $parent = $cRepo->findOneBy(array('title' => $catList[$i - 1]));
                    if($parent){
                        $existCat->setParent($parent);
                        $em->persist($existCat);
                        $em->flush();
                    }
                } else {
                    if(count($catList) == 1){
                        $existCat->setCanonical($name[$i]);
                        $existCat->setDescription($description);
                        $existCat->setImage($image);
                        $em->persist($existCat);
                        $em->flush();
                    }
                }
            }
        }

        if(!empty($category)){
            return $category;
        } elseif (!empty($existCat)){
            return $existCat;
        } else {
            return false;
        }
    }



}
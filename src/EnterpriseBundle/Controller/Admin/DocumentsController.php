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
            $settings->setAdvanced($set);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($settings);
        $em->flush();

        return new JsonResponse(array('success' => true));
    }

    /**
     * @Route("/excelprepare")
     */
    function savePriceSettingsAction(Request $request){

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

        $fileLoader = $this->get('file_uploader');
        $name = $fileLoader->save($_FILES['file']);

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

        file_put_contents($name, iconv("WINDOWS-1251", "UTF-8", file_get_contents($name)));

        $csvFile = fopen($name, "r");
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
    function addToShopAction(Request $request){

        $file = $this->get('file_uploader')->save($_FILES['file']);
        $helpers = $this->get('helpers');
        $helpers->toUTF8($file);

        $fields = $this->getDoctrine()->getRepository('EnterpriseBundle:SellerSettings')
            ->findOneBy(array(
                'seller_id' => 5
            ));

        $category = $fields->getCategory();
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

            $x = $this->createCategories($line[$category]);
            $p = $x->getProducts();

            $product->setCategory($this->createCategories($line[$category]));
            $product->setVendorCode($line[$vendor]);
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

    function createCategories($string){

        $cRepo = $this->getDoctrine()->getRepository('EnterpriseBundle:Category');
        $em = $this->getDoctrine()->getManager();
        $catList = explode('/', $string);

        for($i = 0; $i < count($catList); $i++){

            $existCat = $cRepo->findOneBy(array('title' => $catList[$i]));

            if(!$existCat){

                $category = new Category;
                $category->setTitle($catList[$i]);
                $category->setCanonical($catList[$i]);
                $category->setImage($catList[$i]);
                $category->setDescription($catList[$i]);
                $em->persist($category);
                $em->flush();

                if($i > 0){
                    $parent = $cRepo->findOneBy(array('title' => $catList[$i-1]));
                    if($parent){
                        $category->setParent($parent);
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
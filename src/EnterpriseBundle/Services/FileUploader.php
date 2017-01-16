<?php
namespace EnterpriseBundle\Services;

class FileUploader {

    protected $root = '/var/www/job/web/';
    protected $maxsize = '3mb'; //(int) kb, gb, mb
    protected $allowed = ['rar','zip','doc','docx', 'mp3', 'xls', 'jpg', 'png', 'gif', 'jpeg'];
    protected $path = array(
        'rar' => 'files/documents/',
        'zip' => 'files/documents/',
        'doc' => 'files/documents/',
        'docx' => 'files/documents/',
        'xls' => 'files/documents/',
        'jpg' => 'files/images/',
        'png' => 'files/images/',
        'gif' => 'files/images/',
        'jpeg' => 'files/images/',
        'mp3' => 'files/media/'
    );

    public function save($file){

        if(empty($file)) return 'no file';

        $goodName = $this->checkName($file);
        $goodType = $this->checkType($file);
        $goodSize = $this->checkSize($file);

        if($goodName && $goodSize && $goodType){

            return $this->persist($file);
        } else {
            return array(
                'name' => $goodName,
                'size' => $goodSize,
                'type' => $goodType
            );
        }
    }

    protected function checkName($file){

        $p = '/^[a-zA-Zа-яА-ЯёЁ0-9_-]{1,255}[.][a-z]{3,4}$/';

        if(preg_match($p, $file['name'])){
            return true;
        } else {
            return false;
        }
    }

    public function checkType($file){

        $pos = strpos($file['name'], '.');
        $type = substr($file['name'], ++$pos);

        if(!empty($this->path[$type])){
            return true;
        } else {
            return false;
        }
    }

    public function checkSize($file){

        $integer = intval($this->maxsize);
        $dimension = preg_replace('/\d/','',$this->maxsize);

        switch ($dimension){
            case 'kb' : $zero = '000';
                break;
            case 'mb' : $zero = '000000';
                break;
            case 'gb' : $zero = '000000000';
                break;
            default   : $zero = '000000';
        }

        if($file['size'] < $integer.$zero){
            return true;
        } else {
            return false;
        }
    }

    public function getExtension($file){
        $pos = strpos($file['name'], '.');
        $type = substr($file['name'], ++$pos);
        return $type;
    }

    public function getName($file){
        $pos = strpos($file['name'], '.');
        $name = substr($file['name'], 0, $pos);
        return $name;
    }

    public function getPath($file){
        $pos = strpos($file['name'], '.');
        $type = substr($file['name'], ++$pos);
        return $this->path[$type];
    }

    public function generateName($file){
        return md5(uniqid($this->getName($file)));
    }

    public function persist($file){
        $name = $this->generateName($file);
        $exe = $this->getExtension($file);
        $path = $this->getPath($file);
        $uploadedFile = $this->root.$path.$name.'.'.$exe;
        move_uploaded_file($file['tmp_name'], $uploadedFile);
        return $path.$name.'.'.$exe;
    }

}
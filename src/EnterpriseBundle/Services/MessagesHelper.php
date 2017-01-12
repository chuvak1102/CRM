<?php
namespace EnterpriseBundle\Services;

class MessagesHelper {

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
}
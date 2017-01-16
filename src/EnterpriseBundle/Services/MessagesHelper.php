<?php
namespace EnterpriseBundle\Services;

class MessagesHelper {

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
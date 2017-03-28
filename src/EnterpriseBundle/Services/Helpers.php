<?php

namespace EnterpriseBundle\Services;

use Symfony\Component\Config\Definition\Exception\Exception;

class Helpers {

    function transliterize($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',    'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }
    function stringToUrl($str) {

        $str = $this->transliterize($str);
        $str = strtolower($str);
        $str = str_replace(chr(34), '', $str);
        $str = str_replace(chr(44), '', $str);
        $str = preg_replace('/[^(\x20-\x7F)]*/','', $str);
        $str = str_replace(' ','-',$str);
        return $str;
    }

    function toUTF8($filePath){
        file_put_contents($filePath, iconv("WINDOWS-1251", "UTF-8", file_get_contents($filePath)));
    }

    function stringToAlias($str){
        $forbidden = array(chr(34), chr(44), chr(46));
        $str = $this->transliterize($str);
        $str = strtolower($str);
        $str = str_replace($forbidden, '', $str);
        $str = str_replace(chr(47), '-', $str); // slash
        $str = preg_replace('/[^(\x20-\x7F)]*/','', $str);
        $str = str_replace(' ','-',$str);
        return $str;
    }
    
    function oneImageFromMany($string){
        $delimiter = strpos($string, ',');
        return substr($string, 0, $delimiter);
    }
}
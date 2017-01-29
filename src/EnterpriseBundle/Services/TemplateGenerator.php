<?php

namespace EnterpriseBundle\Services;

use Symfony\Component\Config\Definition\Exception\Exception;

class TemplateGenerator {

    public $exist;
    public $templateName;

    function __construct(){
    }

    function createStatic($route, $html){

        $root = str_replace('/web', '', $_SERVER['DOCUMENT_ROOT']);
        $sitePrefix = str_replace('/var/www/', '', $root);

        $dir = '/var/www/'.$sitePrefix.'/app/Resources/views/default/';
        $template =

<<<TEMPLATE
{% extends ':default:base.html.twig' %}
{% block content %}
    <h1>$html</h1>
{% endblock %}
TEMPLATE;

        if(!file_exists($dir.$route.'.html.twig')) {

            if($file = fopen($dir.$route.'.html.twig', "w")){
                fwrite($file, $template);
                fclose ($file);

                $this->templateName =  $route.'.html.twig';

                return $this;
            } else {

                throw new \Exception('Can not create file: '.$dir.$route.'.html.twig');
            }

        } else {

            if($file = fopen($dir.$route.'.html.twig', "w")){
                fwrite($file, $template);
                fclose ($file);

                $this->exist = true;

                return $this;
            } else {

                throw new \Exception('Can not open file: '.$dir.$route.'.html.twig');
            }
        }
    }
}
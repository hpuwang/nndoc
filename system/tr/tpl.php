<?php
/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr_tpl {
    static function assets($app=''){
        if(!$app) $app = tr::getApp();
        if(!$app){
            $path= "";
        }else{
            $path = "/app/".$app."/assets";
        }
        echo $path;
    }

    static function g_assets(){
        $path = "/public/assets";
        echo $path;
    }

    static function lang($str,$app='',$replace=array()){
        echo tr::lang($str,$app,$replace);
    }


    static function url($action,$string=''){
        echo tr::url($action,$string);
    }

    static function style($url){
            echo style($url);
        }

    static function script($url){
            echo script($url);
        }
}
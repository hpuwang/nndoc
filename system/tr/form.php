<?php
/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr_form{
    private $method="POST";
    private $action='';
    private $formOtherStr = "";


    static function begin($config){
        $str = "<form ";
        if($config){
            if(!isset($config['action'])){
                $config['action'] = "";
            }

            if(!isset($config['method'])){
                $config['method'] = "POST";
            }

            foreach($config as $k=>$v){
                $str .= $k."='".$v."'";
            }
        }
        $str .= " >";
        return $str;
    }

    static function field($type,$name,$aliName,$attribute){
        if($type == "text"){
            $str = "<input type";
        }
    }

    static function validate(){

    }

    static function end(){
        return "</form>";
    }




}
<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 16/3/3
 * Time: 上午9:31
 */
class doc_service_user {

    function checkLogin(){
        $pathinfo = tr::getPathInfo();
        $action = tr::getUrlAction($pathinfo);
        if(!$action) return false;

        if(in_array($action[0],array('doc_controller_user@login',"doc_controller_user@logout","doc_controller_project@index","doc_controller_project@view"))){
            return true;
        }
        $userInfo = isset($_SESSION[doc_service_const::LOGIN_KEY])?$_SESSION[doc_service_const::LOGIN_KEY]:null;
        return $userInfo ? true:false;
    }


    function setLogin($userInfo){
        $_SESSION[doc_service_const::LOGIN_KEY]=$userInfo;
        return true;
    }

    function getLogin(){
        $userInfo = isset($_SESSION[doc_service_const::LOGIN_KEY])?$_SESSION[doc_service_const::LOGIN_KEY]:null;
        return $userInfo;
    }
}
<?php
/**
 * User: peter.wang
 * Date: 2015/12/17
 * Time: 13:53
 */
//登陆检查
tr_hook::add("route_start",function(){
    $obj = new doc_service_user();
    $loginInfo = $obj->checkLogin();
    if(!$loginInfo){
        redirect(url('doc_controller_user@login'));
    }
});
//日志记录
tr_hook::add("route_start",function(){
    $dao = new doc_dao_log();
    $dao->addLog();
});


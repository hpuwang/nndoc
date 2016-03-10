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
//文档列表
tr_hook::add("api_list",function($params){
    list($pid,$viewid) = $params;
    $groupDao = new doc_service_project();
    $list=$groupDao->getApiList($pid);
    $params = array();
    $params['list'] = $list;
    $params['id'] = $viewid;
    return tr_controller::render("doc::project/apiMenuList",$params);
});

//文档列表
tr_hook::add("api_top_menu",function($pid){
    $params = array();
    $params['pid'] = $pid;
    return tr_controller::render("doc::project/apiTopMenu",$params);
});

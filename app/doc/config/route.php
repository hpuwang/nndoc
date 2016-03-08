<?php
return array(
        "/"=>array("doc_controller_index@index","首页","入口"),
    "/project-:number"=>array("doc_controller_project@index","项目","入口"),
    "/login"=>array("doc_controller_user@login","登陆","用户"),
    "/logout"=>array("doc_controller_user@logout","退出","用户"),
    "/addproject"=>array("doc_controller_project@add","添加","项目"),
    "/apiview-:number"=>array("doc_controller_project@view","查看文档","项目"),
    "/apiadd-:number"=>array("doc_controller_project@addApi","添加api文档","项目"),
    "/edit-:number"=>array("doc_controller_project@editApi","编辑api文档","项目"),
    "/structadd-:number"=>array("doc_controller_project@addStruct","添加结构体","项目"),
    "/user"=>array("doc_controller_user@index","用户管理","用户"),
    "/user/add"=>array("doc_controller_user@add","用户添加","用户"),
    "/user/muser"=>array("doc_controller_user@muser","修改用户信息","用户"),
    "/delapi-:number"=>array("doc_controller_project@delApi","删除api文档","项目"),
    "/editproject-:number"=>array("doc_controller_project@edit","编辑","项目"),
);
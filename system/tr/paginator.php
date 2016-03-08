<?php
/**
 * 分页类
 */
/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr_paginator extends tr_db{
    protected static $_instance = null;
    static $tablename='';

    static function pager($table,$where = array(), $orderBy = "", $page=0,$currentUrl="",$pageSize=0,$groupBy = ""){
        self::$tablename = $table;
        if(!$page){
            $page = (int) tr::getParam("p");
        }
        $page = $page <1 ?1:$page;
        $getPageSize = (int) tr::getParam("pageSize");
        if($getPageSize){
            $pageSize = $getPageSize;
        } else{
            $pageSizeConfig = tr::config()->get("app.page_size");
            $pageSize = $pageSize?$pageSize:$pageSizeConfig;
        }
        $limit = ($page-1)*$pageSize;
        $list = self::gets($where,$orderBy,$limit,$pageSize,$groupBy,true);
        $count = self::getTotal();
        $markup = self::parse($pageSize,$page,$count,$currentUrl);
        return array($list,$markup);
    }

    static function pagerSql($sql,$page=0,$currentUrl="",$pageSize=0){
        if(!$page){
            $page = (int) tr::getParam("p");
        }
        $page = $page <1 ?1:$page;
        $getPageSize = (int) tr::getParam("pageSize");
        if($getPageSize){
            $pageSize = $getPageSize;
        } else{
            $pageSizeConfig = tr::config()->get("app.page_size");
            $pageSize = $pageSize?$pageSize:$pageSizeConfig;
        }
        $limit = ($page-1)*$pageSize;
        $sql .= " LIMIT ".$limit.", ".$pageSize;
        $list = self::selectAll($sql,array(),true);
        $count = self::getTotal();

        $markup = self::parse($pageSize,$page,$count,$currentUrl);
        return array($list,$markup);
    }

    static function parse($pageSize, $page, $totalNum,$currentUrl=''){
        if(!$currentUrl) $currentUrl = tr::getPathInfo();
        $data['page'] = (int) $page;
        $data['pageSize'] = (int) $pageSize;
        $data['totalNum'] = (int) $totalNum;
        $data['page'] = $data['page'] < 0 ? 1 : $data['page'];
        $data['pageCount'] = ($data['totalNum'] % $data['pageSize']) == 0 ?  ($data['totalNum'] / $data['pageSize']) : ceil($data['totalNum'] / $data['pageSize']);
        $data['previous'] = $data['page']-1 < 0 ? 1 : $data['page']-1;
        $data['next'] = $data['page']+1 < $data['pageCount'] ? $data['page']+1 : $data['pageCount'];
        $data['current'] = $data['page'];
        $data['totalData'] = $data['totalNum'];
        $data['currentUrl'] = $currentUrl;
        return tr_controller::tpl()->render("paginator",$data);
    }


    static function pagiUrl($page, $pageCount,$currentUrl){
        if(!$currentUrl) $currentUrl = tr::getPathInfo();
        $page = intval($page);
        $page = $page > $pageCount ? $pageCount : $page;
        $page = $page < 1 ? 1 :  $page;
        $request = array();
        if(stristr(":",$currentUrl)){
            $currentUrl .= str_ireplace(":p", $page, $currentUrl);
        }else{
            $request["p"]=$page;
        }
        $param = tr::getParam();
        $request = $request+$param;
        if($request){
            $currentUrl .="?".http_build_query($request);
        }
        return $currentUrl;
    }
}
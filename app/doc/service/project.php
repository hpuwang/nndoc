<?php
class doc_service_project {

    function getApiList($pid){
        $groupDao = new doc_dao_group();
        $where=array();
        $where['pid'] = $pid;
        $group = $groupDao->gets($where," fsort asc");
        $apiDao = new doc_dao_api();
        if($group){
            foreach($group as $k=>$v){
                $list = $apiDao->gets(array("gid"=>$v['id']),"fsort Asc");
                $group[$k]['child']=$list;
            }
        }
        return $group;
    }

}
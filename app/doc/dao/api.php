<?php
class doc_dao_api extends tr_db{
    static $tablename='doc_api';

    function getById($id){
        return self::get(array("id"=>$id));
    }

    function getAllApi($projectId){

        $where=array();
        $where['pid'] = $projectId;

        $info = self::gets($where,"fsort asc,ctime desc");
        return $info;
    }

    function addDoc($title,$content,$pid,$cuid,$group,$fsort=0){
        $data=array();
        $data['title'] = $title;
        $data['content'] = $content;
        $data['fsort'] = $fsort;
        $data['ctime'] = date('Y-m-d H:i:s');
        $data['pid'] = $pid;
        $data['cuid'] = $cuid;
        $data['gid'] = $group;
        return self::insert($data);
    }

    function editDoc($title,$content,$fsort,$group,$id){
        $data=array();
        $data['title'] = $title;
        $data['content'] = $content;
        $data['fsort'] = $fsort;
        $data['gid'] = $group;
        return self::update($data,array("id"=>$id));
    }

}
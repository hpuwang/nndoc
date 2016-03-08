<?php
class doc_dao_api extends tr_db{
    static $tablename='doc_api';

    function getById($id){
        return self::get(array("id"=>$id));
    }

    function getAllApi($projectId,$ftype=0){

        $where=array();
        $where['pid'] = $projectId;
        $where['ftype']=$ftype;

        $info = self::gets($where,"fsort asc,ctime desc");
        return $info;
    }

    function addDoc($title,$content,$pid,$cuid,$type=0,$fsort=0){
        $data=array();
        $data['title'] = $title;
        $data['content'] = $content;
        $data['fsort'] = $fsort;
        $data['ctime'] = date('Y-m-d H:i:s');
        $data['pid'] = $pid;
        $data['cuid'] = $cuid;
        $data['ftype'] = $type;
        return self::insert($data);
    }

    function editDoc($title,$content,$fsort,$id){
        $data=array();
        $data['title'] = $title;
        $data['content'] = $content;
        $data['fsort'] = $fsort;
        return self::update($data,array("id"=>$id));
    }

}
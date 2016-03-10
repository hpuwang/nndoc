<?php
/**
 * User: peter.wang
 * Url: http://2tag.cn
 * Date: 16/3/10
 * Time: 上午9:09
 */
class doc_dao_group extends tr_db{
    static $tablename='doc_group';


    function getAll(){
        $data = self::gets();
        return $data;
    }

    function addGroup($name,$pid,$fsort){
        $data=array();
        $data['name'] = $name;
        $data['fsort'] = $fsort;
        $data['pid'] = $pid;
        return self::insert($data);
    }

    function getById($id){
        return self::get(array("id"=>$id));
    }

    function editGroup($name,$fsort,$id){
        $data=array();
        $data['name'] = $name;
        $data['fsort'] = $fsort;
        return self::update($data,array("id"=>$id));
    }

}
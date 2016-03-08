<?php
class doc_dao_project extends tr_db{
    static $tablename='doc_project';

    function getAll(){
        $data = self::gets();
        return $data;
    }

    function add($name,$content){
        $data = array();
        $data['name'] = $name;
        $data['descr'] = $content;
        $data['ctime'] = date('Y-m-d H:i:s');
        self::insert($data);
        return true;
    }

    function getById($id){
        return self::get(array("id"=>$id));
    }



}
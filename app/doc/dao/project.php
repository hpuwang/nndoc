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

    function edit($name,$content,$id){
        $data = array();
        $data['name'] = $name;
        $data['descr'] = $content;
        $data['mtime'] = date('Y-m-d H:i:s');
        self::update($data,array("id"=>$id));
        return true;
    }

    function getById($id){
        return self::get(array("id"=>$id));
    }

    function getByName($name,$id=0){
        $data= array();
        $data['name']=$name;
        if($id){
            $data['id']=array("!=",$id);
        }
        return self::get($data);
    }



}
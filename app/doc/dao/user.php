<?php
class doc_dao_user extends tr_db{
    static $tablename='doc_user';

    function getUser($uid){
        return self::get(array("id"=>$uid));
    }

    function getByEmail($email){
        return self::get(array("email"=>$email));
    }

    function getByNickname($nickname,$uid=0){
        $where=array();
        $where['nickname'] = $nickname;
        if($uid) $where['id'] = array("!=",$uid);
        return self::get($where);
    }

    function getAll(){
        $data = self::gets();
        return $data;
    }

    function checkLogin($email,$pwd){
        $pwd = md5($pwd);
        $where=array();
        $where['email'] = $email;
        $where['pwd'] = $pwd;
        $check = self::get($where);
        unset($check['pwd']);
        return $check;
    }


    function add($email,$nickname,$pwd){
        $data = array();
        $data['email'] = $email;
        $data['nickname'] = $nickname;
        $data['pwd'] = md5($pwd);
        return self::insert($data);
    }


    function mpwd($userId,$pwd,$nickname){
        $data = array();
        if($pwd){
            $data['pwd'] = md5($pwd);
        }
        $data['nickname'] = $nickname;
        return self::update($data,array("id"=>$userId));
    }
}
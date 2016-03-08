<?php
class doc_dao_log extends tr_db{
    static $tablename='doc_log';

    function addLog(){
        $service = new doc_service_user();
        $userInfo = $service->getLogin();
        if(!$userInfo) return true;
        $app = tr::$currentApp;
        $data=array();
        $data['action'] = $app['action'];
        $data['action_name'] = $app['actionName'];
        $data['uid'] = $userInfo['id'];
        self::insert($data);
        return;
    }

}
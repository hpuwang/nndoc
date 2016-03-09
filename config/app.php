<?php
return array(
    "namespaces" => array('tr'),
    "apps_namespaces" => array('doc','admin'),
    "apps"=>array("doc",'admin'),
    "reset_apps"=>array(),
    "session"=>array("timeout"=>3600*12),
    "tpl_skin"=>"default",//皮肤
    "page_size"=>10,
    "db" => array("default"=>array(
        "auto_time" => false,
        "prefix" => "pt_",
        "encode" => "",
        "master"=> array(
            "host" => "localhost",
            "user" => "root",
            "port"=>"3306",
            "password" => "root",
            "db_name" => "pt",
        )
    )),
    "memcached"=>array(
//        'server_1' => array(
//            'hostname'   => '127.0.0.1',
//            'port'   => 11211,
//            'weight' => 1
//        ),
    ),
    "logPath"=>ROOT_PATH."/log",
    "debug_value"=>"9527",
    "auth_key"=>"wetree@34234!(*&^><wwww",
    "error404_tpl"=>"error404",
    "msg_tpl"=>"msg",
    "is_write"=>0,
    "rand_job"=>0.3
);

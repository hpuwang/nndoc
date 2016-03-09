<?php
return array(
    "debug" => true,
    "db" => array("default"=>array(
        "auto_time" => true,
        "prefix" => "nnd_",
        "encode" => "",
        "master"=> array(
            "host" => "localhost",
            "user" => "root",
            "port"=>"3306",
            "password" => "",
            "db_name" => "nndoc",
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
);
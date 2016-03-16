## 牛牛api文档系统

### 安装步骤

- 新建nndoc数据库

- 导入nndoc.sql 数据库

- 更改config/dev.app.php 里面的配置为你自己的数据库连接信息

```

"db" => array("default"=>array(
        "auto_time" => false,
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
    
```

将数据库的配置更改为你自己的配置

- 默认登陆密码

admin@admin.com/111111a

- 需要服务器支持pathinfo

- nginx 服务器默认不支持




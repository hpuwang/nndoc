## 牛牛api文档系统

### 安装步骤

- 新建nndoc数据库

- 导入nndoc.sql 数据库

- 更改config/dev.app.php 里面的配置

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



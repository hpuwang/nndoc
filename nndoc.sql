# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.10)
# Database: nndoc
# Generation Time: 2016-03-10 03:33:33 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table nnd_admin_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nnd_admin_user`;

CREATE TABLE `nnd_admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL DEFAULT '0',
  `pwd` varchar(200) NOT NULL DEFAULT '0',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `nnd_admin_user` WRITE;
/*!40000 ALTER TABLE `nnd_admin_user` DISABLE KEYS */;

INSERT INTO `nnd_admin_user` (`id`, `username`, `pwd`, `ctime`, `mtime`)
VALUES
	(1,'admin','111111','2016-03-09 09:11:31','2016-03-09 09:11:31');

/*!40000 ALTER TABLE `nnd_admin_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nnd_doc_api
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nnd_doc_api`;

CREATE TABLE `nnd_doc_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `fsort` int(11) NOT NULL,
  `content` text NOT NULL,
  `pid` int(11) NOT NULL,
  `cuid` int(11) NOT NULL COMMENT '创建人',
  `gid` int(11) NOT NULL DEFAULT '0',
  `ctime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文档数据表';

LOCK TABLES `nnd_doc_api` WRITE;
/*!40000 ALTER TABLE `nnd_doc_api` DISABLE KEYS */;

INSERT INTO `nnd_doc_api` (`id`, `title`, `fsort`, `content`, `pid`, `cuid`, `gid`, `ctime`, `mtime`)
VALUES
	(5,'全局声明版本说明',0,'**简要描述：**\r\n\r\n###### 触屏版无版本之分，永远都只取得最新的版本(包括beta 和 online)\r\n\r\n**参数：**\r\n\r\n|apiVersion版本号|上线时间|app客户端类型|客户端版本号|\r\n|:----    |:---|:----- |-----   |\r\n|a5.01 |2016-02-23  |android |2.1.1   |\r\n|a5.02 |2016-03-30  |android |2.1.3   |\r\n|i5.01 |2016-02-23  |ios |2.1.1   |\r\n|i5.02 |2016-03-30  |ios |2.1.3   |\r\n\r\n\r\n\r\n',5,1,1,'2016-03-04 17:48:58','2016-03-10 10:44:51'),
	(7,'获取一级类目',1,'**简要描述：**\r\n\r\n- 获取一级类目\r\n\r\n**请求URL：**\r\n- ` general/GetFirstCategory`\r\n\r\n**请求方式：**\r\n- POST\r\n\r\n**请求示例**\r\n\r\n```\r\n{\r\n\"token\":\"12345678901234567890123456789012\",\r\n\"areaCode\":\"CS000016-0-0-0\",\r\n\"body\":{\r\n\"versionNo\": \"848161c77c5928a384e4d6bfff759dbeCS000016\"\r\n}\r\n}\r\n\r\n```\r\n\r\n**参数：**\r\n\r\n|参数名|必选|类型|说明|\r\n|:----    |:---|:----- |-----   |\r\n|versionNo|是|string|版本号|\r\n\r\n\r\n **返回示例**\r\n\r\n```\r\n{\r\n    \"elapsedTime\": \"0.0880\",\r\n    \"errorCode\": 0,\r\n    \"errorDesc\": \"\",\r\n    \"body\": {\r\n        \"CategoryList\": [\r\n            {\r\n                \"siSeq\": \"xxx\",\r\n                \"type\": 0,\r\n                \"appName\": \"热门分类\"\r\n            },\r\n            {\r\n                \"siSeq\": \"C19510\",\r\n                \"type\": 1,\r\n                \"appName\": \"母婴、玩具\"\r\n            },\r\n            {\r\n                \"siSeq\": \"C19416\",\r\n                \"type\": 1,\r\n                \"appName\": \"鞋靴、箱包\"\r\n            },\r\n            {\r\n                \"siSeq\": \"C77199\",\r\n                \"type\": 1,\r\n                \"appName\": \"（无线测试用）\"\r\n            },\r\n            {\r\n                \"siSeq\": \"C56778\",\r\n                \"type\": 1,\r\n                \"appName\": \"shirley测试群组\"\r\n            },\r\n            {\r\n                \"siSeq\": \"C76726\",\r\n                \"type\": 1,\r\n                \"appName\": \"路飞测试\"\r\n            },\r\n            {\r\n                \"siSeq\": \"C76918\",\r\n                \"type\": 1,\r\n                \"appName\": \"CATEGOTYTEST\"\r\n            },\r\n            {\r\n                \"siSeq\": \"C19400\",\r\n                \"type\": 1,\r\n                \"appName\": \"手机、数码、配件\"\r\n            },\r\n            {\r\n                \"siSeq\": \"C19322\",\r\n                \"type\": 1,\r\n                \"appName\": \"生鲜、冷冻食品、海鲜\"\r\n            },\r\n            {\r\n                \"siSeq\": \"C19411\",\r\n                \"type\": 1,\r\n                \"appName\": \"食品、酒水、切糕\"\r\n            },\r\n            {\r\n                \"siSeq\": \"C19558\",\r\n                \"type\": 1,\r\n                \"appName\": \"家用电器\"\r\n            },\r\n            {\r\n                \"siSeq\": \"C53328\",\r\n                \"type\": 1,\r\n                \"appName\": \"操作日志测试\"\r\n            }\r\n        ],\r\n\"versionNo\": \"848161c77c5928a384e4d6bfff759dbeCS000016\"\r\n    }\r\n}\r\n\r\n```\r\n\r\n **返回参数说明**\r\n\r\n|参数名|类型|说明|\r\n|:-----  |:-----|-----|\r\n|CategoryList|CategoryList|siSeq 分类id appName 类目名称 type 类型 0：热门分类 1：一般分类 一级类目列表|\r\n\r\n######CategoryList\r\n|参数名|类型|说明|\r\n|:-----  |:-----|-----|\r\n|siSeq|string|分类id|\r\n|appName|string|类目名称|\r\n|type|int|类型 0：热门分类 1：一般分类 一级类目列表|\r\n\r\n **备注**\r\n\r\n\r\n',5,1,1,'2016-03-04 18:21:52','2016-03-04 18:21:52'),
	(8,'获取二三级类目',2,'**简要描述：**\r\n\r\n- 常用分类接口由大数据提供，api不再提供\r\n\r\n**请求URL：**\r\n- ` general/GetChildCategory`\r\n\r\n**请求方式：**\r\n- POST\r\n\r\n**请求示例**\r\n\r\n```\r\n{\r\n\"token\":\"12345678901234567890123456789012\",\r\n\"body\":\r\n{\r\n\"siSeq\":\" CG100483\",\r\n\"type\":1,\r\n\"versionNo\":\"\"\r\n}\r\n}\r\n\r\n```\r\n\r\n**参数：**\r\n\r\n|参数名|必选|类型|默认值|说明|\r\n|:----    |:---|:----- |:----- |-----   |\r\n|siSeq|是|string|0|一级类目id|\r\n|type|是|int|1|0：热门类目 1：一般类目 |\r\n|version|是|string||版本信息|\r\n **返回示例**\r\n\r\n```\r\n  {\r\n    \"elapsedTime\": \"0.0905\",\r\n    \"errorCode\": 0,\r\n    \"errorDesc\": \"\",\r\n    \"body\": {\r\n        \"CategoryTree\": [\r\n            {\r\n                \"siSeq\": \"C76845\",\r\n                \"type\": 1,\r\n                \"appName\": \"自订馆测试\",\r\n\" ==\": 1,\r\n                \"child\": []\r\n            },\r\n            {\r\n                \"siSeq\": \"C76656\",\r\n                \"type\": 1,\r\n                \"appName\": \"test_20092\",\r\n\"hasMore\": 1,\r\n                \"child\": [\r\n                    {\r\n                        \"siSeq\": \"C76680\",\r\n                        \"type\": 1,\r\n                        \"appName\": \"12\",\r\n                        \"icon\": \"/C/web/layout/kk/20150903/20150903093621_kk-1.png\"\r\n                    },\r\n                    {\r\n                        \"siSeq\": \"C76667\",\r\n                        \"type\": 1,\r\n                        \"appName\": \"tests_20092\",\r\n                        \"icon\": \"/C/web/layout/kk/20150903/20150903093621_kk-1.png\"\r\n                    }\r\n                ]\r\n            }\r\n        ],\r\n        \"banner\": {\r\n            \"picUrl\": \"/C/web/layout/kk/20150903/20150903093621_kk-1.png\",\r\n            \"link\": \"http://www.xxx.com\",\r\n            \"bannerType\": 1\r\n        },\r\n        \"versionNo\": \"848161c77c5928a384e4d6bfff759dbeCS000016\",\r\n\"parentSiSeq\": \" C19322\",\r\n\"isHasBanner\": 1\r\n    }\r\n}\r\n\r\n```\r\n\r\n **返回参数说明**\r\n\r\n|参数名|类型|说明|\r\n|:-----  |:-----|-----                           |\r\n|CategoryTree|CategoryTree[]|二三级类目|\r\n|banner|banner|广告位|\r\n|versionNo|string|版本号|\r\n|parentSiSeq|string|请求一级类目编号|\r\n|isHasBanner|int|是否有广告位 1：有 0：没有|\r\n\r\n###### CategoryTree\r\n|参数名|类型|说明|\r\n|:-----  |:-----|-----                           |\r\n|siSeq |string|类目id|\r\n|siName|string| 默认名称|\r\n|appName|string| 首选名称|\r\n|hasMore|int| 是否有全部商品（1：有全部商品，0：没有商品）|\r\n|icon |string|三级类目的图片 |\r\n|type|int|1：常用类目2：推荐类目3：一般类目|\r\n###### banner\r\n|参数名|类型|说明|\r\n|:-----  |:-----|-----|\r\n|picUrl|| 广告位图片|\r\n|link|| 广告活动url地址|\r\n\r\n **备注**\r\n\r\n\r\n',5,1,1,'2016-03-04 18:38:16','2016-03-04 18:38:16'),
	(9,'登陆',3,'**简要描述：**\r\n\r\n- 用户注册接口\r\n\r\n**请求URL：**\r\n- ` http://xx.com/api/user/register `\r\n\r\n**请求方式：**\r\n- POST\r\n\r\n**请求示例**\r\n\r\n```\r\n{\r\n\"error_code\": 0,\r\n\"data\": {\r\n  \"uid\": \"1\",\r\n  \"username\": \"12154545\",\r\n  \"name\": \"吴系挂\",\r\n  \"groupid\": 2 ,\r\n  \"reg_time\": \"1436864169\",\r\n  \"last_login_time\": \"0\",\r\n}\r\n}\r\n```\r\n\r\n**参数：**\r\n\r\n|参数名|必选|类型|默认值|说明|\r\n|:----    |:---|:----- |:---|-----   |\r\n|username |是  |string ||用户名   |\r\n|password |是  |string || 密码    |\r\n|name     |否  |string || 昵称    |\r\n\r\n **返回示例**\r\n\r\n```\r\n  {\r\n    \"error_code\": 0,\r\n    \"data\": {\r\n      \"uid\": \"1\",\r\n      \"username\": \"12154545\",\r\n      \"name\": \"吴系挂\",\r\n      \"groupid\": 2 ,\r\n      \"reg_time\": \"1436864169\",\r\n      \"last_login_time\": \"0\",\r\n    }\r\n  }\r\n```\r\n\r\n **返回参数说明**\r\n\r\n|参数名|类型|说明|\r\n|:-----  |:-----|-----                           |\r\n|groupid |int   |用户组id，1：超级管理员；2：普通用户  |\r\n\r\n **备注**\r\n\r\n\r\n',5,1,2,'2016-03-10 10:53:45','2016-03-10 10:53:45');

/*!40000 ALTER TABLE `nnd_doc_api` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nnd_doc_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nnd_doc_group`;

CREATE TABLE `nnd_doc_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  `fsort` int(11) NOT NULL DEFAULT '0',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文档分组';

LOCK TABLES `nnd_doc_group` WRITE;
/*!40000 ALTER TABLE `nnd_doc_group` DISABLE KEYS */;

INSERT INTO `nnd_doc_group` (`id`, `name`, `pid`, `fsort`, `ctime`, `mtime`)
VALUES
	(1,'全局',5,0,'2016-03-10 09:28:12','2016-03-10 11:30:12'),
	(2,'会员相关',5,0,'2016-03-10 10:45:10','2016-03-10 10:45:10'),
	(3,'卖场相关',5,0,'2016-03-10 10:45:49','2016-03-10 10:45:49'),
	(4,'购物流程相关',5,0,'2016-03-10 10:46:02','2016-03-10 10:46:02');

/*!40000 ALTER TABLE `nnd_doc_group` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nnd_doc_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nnd_doc_log`;

CREATE TABLE `nnd_doc_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(200) NOT NULL DEFAULT '0' COMMENT '操作名称',
  `uid` int(11) NOT NULL DEFAULT '0',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action_name` varchar(200) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户操作记录表';

LOCK TABLES `nnd_doc_log` WRITE;
/*!40000 ALTER TABLE `nnd_doc_log` DISABLE KEYS */;

INSERT INTO `nnd_doc_log` (`id`, `action`, `uid`, `ctime`, `mtime`, `action_name`)
VALUES
	(1,'doc_controller_project@view',1,'2016-03-08 16:02:59','2016-03-08 16:02:59','查看文档'),
	(2,'doc_controller_project@view',1,'2016-03-08 16:03:30','2016-03-08 16:03:30','查看文档'),
	(3,'doc_controller_project@view',1,'2016-03-08 16:05:15','2016-03-08 16:05:15','查看文档'),
	(4,'doc_controller_project@view',1,'2016-03-08 16:05:22','2016-03-08 16:05:22','查看文档'),
	(5,'doc_controller_project@view',1,'2016-03-08 17:04:23','2016-03-08 17:04:23','查看文档'),
	(6,'doc_controller_project@view',1,'2016-03-08 17:06:21','2016-03-08 17:06:21','查看文档'),
	(7,'doc_controller_project@view',1,'2016-03-08 17:09:48','2016-03-08 17:09:48','查看文档'),
	(8,'doc_controller_project@view',1,'2016-03-08 17:10:27','2016-03-08 17:10:27','查看文档'),
	(9,'doc_controller_project@view',1,'2016-03-08 17:11:24','2016-03-08 17:11:24','查看文档'),
	(10,'doc_controller_project@view',1,'2016-03-08 17:12:09','2016-03-08 17:12:09','查看文档'),
	(11,'doc_controller_project@view',1,'2016-03-08 17:12:52','2016-03-08 17:12:52','查看文档'),
	(12,'doc_controller_project@view',1,'2016-03-08 17:34:53','2016-03-08 17:34:53','查看文档'),
	(13,'doc_controller_project@view',1,'2016-03-08 17:35:18','2016-03-08 17:35:18','查看文档'),
	(14,'doc_controller_project@view',1,'2016-03-08 17:35:28','2016-03-08 17:35:28','查看文档'),
	(15,'doc_controller_project@view',1,'2016-03-08 17:35:45','2016-03-08 17:35:45','查看文档'),
	(16,'doc_controller_project@view',1,'2016-03-08 17:35:47','2016-03-08 17:35:47','查看文档'),
	(17,'doc_controller_project@view',1,'2016-03-08 17:36:10','2016-03-08 17:36:10','查看文档'),
	(18,'doc_controller_project@view',1,'2016-03-08 17:36:27','2016-03-08 17:36:27','查看文档'),
	(19,'doc_controller_project@view',1,'2016-03-08 17:36:40','2016-03-08 17:36:40','查看文档'),
	(20,'doc_controller_project@view',1,'2016-03-08 17:37:56','2016-03-08 17:37:56','查看文档'),
	(21,'doc_controller_project@view',1,'2016-03-08 17:38:07','2016-03-08 17:38:07','查看文档'),
	(22,'doc_controller_project@view',1,'2016-03-08 17:38:34','2016-03-08 17:38:34','查看文档'),
	(23,'doc_controller_project@view',1,'2016-03-08 17:38:46','2016-03-08 17:38:46','查看文档'),
	(24,'doc_controller_project@view',1,'2016-03-08 17:38:51','2016-03-08 17:38:51','查看文档'),
	(25,'doc_controller_project@view',1,'2016-03-08 17:41:01','2016-03-08 17:41:01','查看文档'),
	(26,'doc_controller_project@view',1,'2016-03-08 17:41:06','2016-03-08 17:41:06','查看文档'),
	(27,'doc_controller_project@view',1,'2016-03-08 17:41:36','2016-03-08 17:41:36','查看文档'),
	(28,'doc_controller_project@view',1,'2016-03-08 17:41:42','2016-03-08 17:41:42','查看文档'),
	(29,'doc_controller_project@view',1,'2016-03-08 17:41:56','2016-03-08 17:41:56','查看文档'),
	(30,'doc_controller_project@view',1,'2016-03-08 17:42:26','2016-03-08 17:42:26','查看文档'),
	(31,'doc_controller_project@view',1,'2016-03-08 17:43:40','2016-03-08 17:43:40','查看文档'),
	(32,'doc_controller_project@view',1,'2016-03-08 17:44:20','2016-03-08 17:44:20','查看文档'),
	(33,'doc_controller_project@view',1,'2016-03-08 17:45:20','2016-03-08 17:45:20','查看文档'),
	(34,'doc_controller_index@index',1,'2016-03-10 09:04:50','2016-03-10 09:04:50','首页'),
	(35,'doc_controller_user@login',1,'2016-03-10 09:04:50','2016-03-10 09:04:50','登陆'),
	(36,'doc_controller_project@index',1,'2016-03-10 09:04:52','2016-03-10 09:04:52','项目'),
	(37,'doc_controller_user@login',1,'2016-03-10 09:04:53','2016-03-10 09:04:53','登陆'),
	(38,'doc_controller_project@index',1,'2016-03-10 09:15:24','2016-03-10 09:15:24','项目'),
	(39,'doc_controller_user@login',1,'2016-03-10 09:15:27','2016-03-10 09:15:27','登陆'),
	(40,'doc_controller_project@addGroup',1,'2016-03-10 09:15:28','2016-03-10 09:15:28','添加组'),
	(41,'doc_controller_user@login',1,'2016-03-10 09:15:28','2016-03-10 09:15:28','登陆'),
	(42,'doc_controller_project@addGroup',1,'2016-03-10 09:15:40','2016-03-10 09:15:40','添加组'),
	(43,'doc_controller_project@addGroup',1,'2016-03-10 09:28:07','2016-03-10 09:28:07','添加组'),
	(44,'doc_controller_user@login',1,'2016-03-10 09:28:08','2016-03-10 09:28:08','登陆'),
	(45,'doc_controller_project@addGroup',1,'2016-03-10 09:28:12','2016-03-10 09:28:12','添加组'),
	(46,'doc_controller_project@view',1,'2016-03-10 09:28:14','2016-03-10 09:28:14','查看文档'),
	(47,'doc_controller_user@login',1,'2016-03-10 09:28:15','2016-03-10 09:28:15','登陆'),
	(48,'doc_controller_project@addGroup',1,'2016-03-10 09:33:31','2016-03-10 09:33:31','添加组'),
	(49,'doc_controller_user@login',1,'2016-03-10 09:33:32','2016-03-10 09:33:32','登陆'),
	(50,'doc_controller_project@index',1,'2016-03-10 09:33:36','2016-03-10 09:33:36','项目'),
	(51,'doc_controller_user@login',1,'2016-03-10 09:33:37','2016-03-10 09:33:37','登陆');

/*!40000 ALTER TABLE `nnd_doc_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nnd_doc_project
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nnd_doc_project`;

CREATE TABLE `nnd_doc_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL COMMENT '名称',
  `descr` text NOT NULL COMMENT '说明',
  `ctime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `nnd_doc_project` WRITE;
/*!40000 ALTER TABLE `nnd_doc_project` DISABLE KEYS */;

INSERT INTO `nnd_doc_project` (`id`, `name`, `descr`, `ctime`, `mtime`)
VALUES
	(5,'app&ipad接口文档','> 本文档是手机api组提供给ios，android,触屏后台接口的文档\r\n\r\n### 项目\r\n- app\r\n- ipad\r\n- 触屏\r\n- 电子屏\r\n','2016-03-04 17:43:33','2016-03-08 11:09:00');

/*!40000 ALTER TABLE `nnd_doc_project` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nnd_doc_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nnd_doc_user`;

CREATE TABLE `nnd_doc_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `nickname` varchar(200) NOT NULL COMMENT '昵称',
  `pwd` varchar(200) NOT NULL,
  `ctime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户信息';

LOCK TABLES `nnd_doc_user` WRITE;
/*!40000 ALTER TABLE `nnd_doc_user` DISABLE KEYS */;

INSERT INTO `nnd_doc_user` (`id`, `email`, `nickname`, `pwd`, `ctime`, `mtime`)
VALUES
	(1,'hpu423@126.com','管理员','49dec5fb8af4eeef7c95e7f5c66c8ae6','2016-03-03 09:22:00','2016-03-03 12:26:53');

/*!40000 ALTER TABLE `nnd_doc_user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

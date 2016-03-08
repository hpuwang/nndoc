-- phpMyAdmin SQL Dump
-- version 4.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-03-08 02:21:37
-- 服务器版本： 5.7.10
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nndoc`
--

-- --------------------------------------------------------

--
-- 表的结构 `nnd_doc_api`
--

CREATE TABLE `nnd_doc_api` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `fsort` int(11) NOT NULL,
  `content` text NOT NULL,
  `ftype` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `cuid` int(11) NOT NULL COMMENT '创建人',
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文档数据表';

--
-- 转存表中的数据 `nnd_doc_api`
--

INSERT INTO `nnd_doc_api` (`id`, `title`, `fsort`, `content`, `ftype`, `pid`, `cuid`, `ctime`, `mtime`) VALUES
(5, '全局声明版本说明', 0, '**简要描述：**\r\n\r\n###### 触屏版无版本之分，永远都只取得最新的版本(包括beta 和 online)\r\n\r\n**参数：**\r\n\r\n|apiVersion版本号|上线时间|app客户端类型|客户端版本号|\r\n|:----    |:---|:----- |-----   |\r\n|a5.01 |2016-02-23  |android |2.1.1   |\r\n|a5.02 |2016-03-30  |android |2.1.3   |\r\n|i5.01 |2016-02-23  |ios |2.1.1   |\r\n|i5.02 |2016-03-30  |ios |2.1.3   |\r\n\r\n\r\n\r\n', 0, 5, 1, '2016-03-04 09:48:58', '2016-03-04 09:48:58'),
(7, '获取一级类目', 1, '**简要描述：**\r\n\r\n- 获取一级类目\r\n\r\n**请求URL：**\r\n- ` general/GetFirstCategory`\r\n\r\n**请求方式：**\r\n- POST\r\n\r\n**请求示例**\r\n\r\n```\r\n{\r\n"token":"12345678901234567890123456789012",\r\n"areaCode":"CS000016-0-0-0",\r\n"body":{\r\n"versionNo": "848161c77c5928a384e4d6bfff759dbeCS000016"\r\n}\r\n}\r\n\r\n```\r\n\r\n**参数：**\r\n\r\n|参数名|必选|类型|说明|\r\n|:----    |:---|:----- |-----   |\r\n|versionNo|是|string|版本号|\r\n\r\n\r\n **返回示例**\r\n\r\n```\r\n{\r\n    "elapsedTime": "0.0880",\r\n    "errorCode": 0,\r\n    "errorDesc": "",\r\n    "body": {\r\n        "CategoryList": [\r\n            {\r\n                "siSeq": "xxx",\r\n                "type": 0,\r\n                "appName": "热门分类"\r\n            },\r\n            {\r\n                "siSeq": "C19510",\r\n                "type": 1,\r\n                "appName": "母婴、玩具"\r\n            },\r\n            {\r\n                "siSeq": "C19416",\r\n                "type": 1,\r\n                "appName": "鞋靴、箱包"\r\n            },\r\n            {\r\n                "siSeq": "C77199",\r\n                "type": 1,\r\n                "appName": "（无线测试用）"\r\n            },\r\n            {\r\n                "siSeq": "C56778",\r\n                "type": 1,\r\n                "appName": "shirley测试群组"\r\n            },\r\n            {\r\n                "siSeq": "C76726",\r\n                "type": 1,\r\n                "appName": "路飞测试"\r\n            },\r\n            {\r\n                "siSeq": "C76918",\r\n                "type": 1,\r\n                "appName": "CATEGOTYTEST"\r\n            },\r\n            {\r\n                "siSeq": "C19400",\r\n                "type": 1,\r\n                "appName": "手机、数码、配件"\r\n            },\r\n            {\r\n                "siSeq": "C19322",\r\n                "type": 1,\r\n                "appName": "生鲜、冷冻食品、海鲜"\r\n            },\r\n            {\r\n                "siSeq": "C19411",\r\n                "type": 1,\r\n                "appName": "食品、酒水、切糕"\r\n            },\r\n            {\r\n                "siSeq": "C19558",\r\n                "type": 1,\r\n                "appName": "家用电器"\r\n            },\r\n            {\r\n                "siSeq": "C53328",\r\n                "type": 1,\r\n                "appName": "操作日志测试"\r\n            }\r\n        ],\r\n"versionNo": "848161c77c5928a384e4d6bfff759dbeCS000016"\r\n    }\r\n}\r\n\r\n```\r\n\r\n **返回参数说明**\r\n\r\n|参数名|类型|说明|\r\n|:-----  |:-----|-----|\r\n|CategoryList|CategoryList|siSeq 分类id appName 类目名称 type 类型 0：热门分类 1：一般分类 一级类目列表|\r\n\r\n######CategoryList\r\n|参数名|类型|说明|\r\n|:-----  |:-----|-----|\r\n|siSeq|string|分类id|\r\n|appName|string|类目名称|\r\n|type|int|类型 0：热门分类 1：一般分类 一级类目列表|\r\n\r\n **备注**\r\n\r\n\r\n', 0, 5, 1, '2016-03-04 10:21:52', '2016-03-04 10:21:52'),
(8, '获取二三级类目', 2, '**简要描述：**\r\n\r\n- 常用分类接口由大数据提供，api不再提供\r\n\r\n**请求URL：**\r\n- ` general/GetChildCategory`\r\n\r\n**请求方式：**\r\n- POST\r\n\r\n**请求示例**\r\n\r\n```\r\n{\r\n"token":"12345678901234567890123456789012",\r\n"body":\r\n{\r\n"siSeq":" CG100483",\r\n"type":1,\r\n"versionNo":""\r\n}\r\n}\r\n\r\n```\r\n\r\n**参数：**\r\n\r\n|参数名|必选|类型|默认值|说明|\r\n|:----    |:---|:----- |:----- |-----   |\r\n|siSeq|是|string|0|一级类目id|\r\n|type|是|int|1|0：热门类目 1：一般类目 |\r\n|version|是|string||版本信息|\r\n **返回示例**\r\n\r\n```\r\n  {\r\n    "elapsedTime": "0.0905",\r\n    "errorCode": 0,\r\n    "errorDesc": "",\r\n    "body": {\r\n        "CategoryTree": [\r\n            {\r\n                "siSeq": "C76845",\r\n                "type": 1,\r\n                "appName": "自订馆测试",\r\n" ==": 1,\r\n                "child": []\r\n            },\r\n            {\r\n                "siSeq": "C76656",\r\n                "type": 1,\r\n                "appName": "test_20092",\r\n"hasMore": 1,\r\n                "child": [\r\n                    {\r\n                        "siSeq": "C76680",\r\n                        "type": 1,\r\n                        "appName": "12",\r\n                        "icon": "/C/web/layout/kk/20150903/20150903093621_kk-1.png"\r\n                    },\r\n                    {\r\n                        "siSeq": "C76667",\r\n                        "type": 1,\r\n                        "appName": "tests_20092",\r\n                        "icon": "/C/web/layout/kk/20150903/20150903093621_kk-1.png"\r\n                    }\r\n                ]\r\n            }\r\n        ],\r\n        "banner": {\r\n            "picUrl": "/C/web/layout/kk/20150903/20150903093621_kk-1.png",\r\n            "link": "http://www.xxx.com",\r\n            "bannerType": 1\r\n        },\r\n        "versionNo": "848161c77c5928a384e4d6bfff759dbeCS000016",\r\n"parentSiSeq": " C19322",\r\n"isHasBanner": 1\r\n    }\r\n}\r\n\r\n```\r\n\r\n **返回参数说明**\r\n\r\n|参数名|类型|说明|\r\n|:-----  |:-----|-----                           |\r\n|CategoryTree|CategoryTree[]|二三级类目|\r\n|banner|banner|广告位|\r\n|versionNo|string|版本号|\r\n|parentSiSeq|string|请求一级类目编号|\r\n|isHasBanner|int|是否有广告位 1：有 0：没有|\r\n\r\n###### CategoryTree\r\n|参数名|类型|说明|\r\n|:-----  |:-----|-----                           |\r\n|siSeq |string|类目id|\r\n|siName|string| 默认名称|\r\n|appName|string| 首选名称|\r\n|hasMore|int| 是否有全部商品（1：有全部商品，0：没有商品）|\r\n|icon |string|三级类目的图片 |\r\n|type|int|1：常用类目2：推荐类目3：一般类目|\r\n###### banner\r\n|参数名|类型|说明|\r\n|:-----  |:-----|-----|\r\n|picUrl|| 广告位图片|\r\n|link|| 广告活动url地址|\r\n\r\n **备注**\r\n\r\n\r\n', 0, 5, 1, '2016-03-04 10:38:16', '2016-03-04 10:38:16');

-- --------------------------------------------------------

--
-- 表的结构 `nnd_doc_project`
--

CREATE TABLE `nnd_doc_project` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL COMMENT '名称',
  `descr` text NOT NULL COMMENT '说明',
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `nnd_doc_project`
--

INSERT INTO `nnd_doc_project` (`id`, `name`, `descr`, `ctime`, `mtime`) VALUES
(5, 'app&ipad接口文档', '> 本文档是手机api组提供给ios，android,触屏后台接口的文档\r\n\r\n### 项目\r\n- app\r\n- ipad\r\n\r\n', '2016-03-04 09:43:33', '2016-03-04 09:43:33');

-- --------------------------------------------------------

--
-- 表的结构 `nnd_doc_user`
--

CREATE TABLE `nnd_doc_user` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `nickname` varchar(200) NOT NULL COMMENT '昵称',
  `pwd` varchar(200) NOT NULL,
  `ctime` timestamp NULL DEFAULT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户信息';


CREATE TABLE `nnd_doc_log` (

`id` INT NOT NULL AUTO_INCREMENT ,

`action` VARCHAR(200) NOT NULL DEFAULT '0' COMMENT '操作名称' ,

`uid` INT NOT NULL DEFAULT '0'  COMMENT '用户uid'  ,

`ctime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',

 `mtime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',

 `action_name` VARCHAR(200) NOT NULL DEFAULT '0' COMMENT '动作名称',

PRIMARY KEY (`id`)

  ) ENGINE = InnoDB CHARSET=utf8 COMMENT = '用户操作记录表';

--
-- 转存表中的数据 `nnd_doc_user`
--

INSERT INTO `nnd_doc_user` (`id`, `email`, `nickname`, `pwd`, `ctime`, `mtime`) VALUES
(1, 'hpu423@126.com', '管理员', '96e79218965eb72c92a549dd5a330112', '2016-03-03 01:22:00', '2016-03-03 04:26:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nnd_doc_api`
--
ALTER TABLE `nnd_doc_api`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `nnd_doc_project`
--
ALTER TABLE `nnd_doc_project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nnd_doc_user`
--
ALTER TABLE `nnd_doc_user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `nnd_doc_api`
--
ALTER TABLE `nnd_doc_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `nnd_doc_project`
--
ALTER TABLE `nnd_doc_project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `nnd_doc_user`
--
ALTER TABLE `nnd_doc_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

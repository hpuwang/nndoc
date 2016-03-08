<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->section('title')?>-牛牛API文档系统</title>
    <!-- zui -->
    <link href="<?php $this->assets('doc');?>/css/zui.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php $this->assets('doc');?>/css/zui-theme.css">
    <link rel="stylesheet" href="<?php $this->assets('doc');?>/css/gcode.css">
    <!--[if lt IE 9]>
    <script src="<?php $this->assets('doc');?>/lib/ieonly/html5shiv.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="<?php $this->assets('doc');?>/lib/ieonly/respond.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="<?php $this->assets('doc');?>/lib/ieonly/excanvas.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="<?php $this->assets('doc');?>/nice-validator/jquery.validator.css">
    <?php echo $this->section('top-static') ?>
</head>
<body>
<nav class="navbar navbar-inverse" role="navigation">
    <div class="container-fluid">
    <!-- 导航头部 -->
    <div class="navbar-header">
        <!-- 移动设备上的导航切换按钮 -->
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-example">
            <span class="sr-only">切换导航</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <!-- 品牌名称或logo -->
        <a class="navbar-brand" href="<?php echo $this->url("doc_controller_index@index"); ?>">牛牛API文档系统<?php echo $this->section('brand') ?></a>
    </div>

    <!-- 导航项目 -->
    <div class="collapse navbar-collapse navbar-collapse-example">
        <ul class="nav navbar-nav">
            <?php echo $this->section('left-menu') ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php echo $this->section('right-menu') ?>
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><?php
                    $service = new doc_service_user();
                    $user= $service->getLogin();
                    if($user):
                    echo $user['nickname'];
                    ?> <b class="caret"></b></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?php echo $this->url("doc_controller_user@logout"); ?>">退出</a></li>
                    <li>
                        <a href="<?php echo $this->url("doc_controller_user@muser"); ?>">修改用户信息</a>
                    </li>
                </ul>
                <?php endif;?>
            </li>
        </ul>
    </div>
        </div>
</nav>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                        <?php echo $this->section('main') ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<footer class="navbar navbar-fixed-bottom" style="margin-top: 30px;margin-left: 50px;">
    <div class="copyright">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-center with-padding">
                    <span>Copyright &copy; design by peter.wang</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="<?php $this->assets('doc');?>/lib/jquery/jquery.js"></script>
<!-- ZUI Javascript组件 -->
<script src="<?php $this->assets('doc');?>/js/zui.min.js"></script>
<?php echo $this->section('bt-static') ?>
<script type="text/javascript" src="<?php $this->assets('doc');?>/js/growl.js"></script>
<script type="text/javascript" src="<?php $this->assets('doc');?>/nice-validator/jquery.validator.js"></script>
<script type="text/javascript" src="<?php $this->assets('doc');?>/nice-validator/local/zh-CN.js"></script>
</body>
</html>
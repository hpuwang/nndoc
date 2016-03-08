<?php $this->layout('doc::api_layout') ?>
<?php $this->start('title') ?><?php echo $this->e($title) ?><?php $this->stop() ?>
<?php $this->start('brand') ?><a  class="navbar-brand" href="<?php echo $this->url("doc_controller_project@index",$info['id']); ?>"><?php echo $this->e($info['name']) ?></a><?php $this->stop() ?>
<?php $this->start('left-menu'); ?>
<?php
$obj = new doc_service_user();
$user = $obj->getLogin();
if($user):
?>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-plus"></i></a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo $this->url("doc_controller_project@addApi",$info['id']);  ?>">接口文档</a></li>
            <li><a href="<?php echo $this->url("doc_controller_project@addStruct",$info['id']);  ?>">结构体</a></li>
        </ul>
    </li>
<?php
endif;
?>
<?php $this->stop();?>
<?php $this->start('right-menu'); ?>
<?php $this->stop();?>
<?php $this->start('main'); ?>
<div class="col-md-2">
    <nav class="menu" data-toggle="menu" style="padding-left: 10px;">
        <ul class="nav nav-primary">
            <li class="active show nav-parent">
                <a href="javascript:;">接口文档<i class="icon-chevron-right nav-parent-fold-icon"></i><i class="icon-chevron-right nav-parent-fold-icon"></i></a>
                <ul class="nav">
                    <?php if($apiDoc):?>
                    <?php foreach($apiDoc as $v):?>
                    <li><a href="<?php echo $this->url("doc_controller_project@view",$v['id']); ?>"><i class="icon-caret-right"></i> <?php echo $v['title']; ?></a></li>
                            <?php endforeach;?>
                    <?php endif;?>
                </ul>
            </li>
            <li class="active show nav-parent">
                <a href="javascript:;">结构体<i class="icon-chevron-right nav-parent-fold-icon"></i><i class="icon-chevron-right nav-parent-fold-icon"></i></a>
                <ul class="nav">
                    <?php if($structDoc):?>
                        <?php foreach($structDoc as $v):?>
                            <li><a href="<?php echo $this->url("doc_controller_project@view",$v['id']); ?>"><i class="icon-caret-right"></i> <?php echo $v['title']; ?></a></li>
                        <?php endforeach;?>
                    <?php endif;?>
                </ul>
            </li>
        </ul>
    </nav>
</div>
<div class="col-md-10">
    <div id="editormd-view" >
        <textarea style="display:none;"><?php echo $info['descr']; ?></textarea>
    </div>
</div>
<?php $this->stop();?>
<?php echo $this->start('top-static') ?>
<link rel="stylesheet" href="<?php $this->assets('doc');?>/js/editor.md/css/editormd.preview.min.css">
<?php $this->stop() ?>
<?php $this->start('bt-static') ?>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/marked.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/prettify.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/raphael.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/underscore.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/sequence-diagram.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/flowchart.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/jquery.flowchart.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/editormd.min.js"></script>

<script type="text/javascript">
    $(function() {
        var testEditormdView;
        testEditormdView = editormd.markdownToHTML("editormd-view", {
            htmlDecode      : "style,script,iframe",  // you can filter tags decode
            emoji           : true,
            taskList        : true,
            tex             : true,  // 默认不解析
            flowChart       : true,  // 默认不解析
            sequenceDiagram : true,  // 默认不解析
        });
    });
</script>
<?php $this->stop();?>


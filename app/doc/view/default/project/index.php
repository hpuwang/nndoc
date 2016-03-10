<?php $this->layout('doc::api_layout') ?>
<?php $this->start('title') ?><?php echo $this->e($title) ?><?php $this->stop() ?>
<?php $this->start('brand') ?><a  class="navbar-brand" href="<?php echo $this->url("doc_controller_project@index",$info['id']); ?>"><?php echo $this->e($info['name']) ?></a><?php $this->stop() ?>
<?php $this->start('left-menu'); ?>
<?php echo tr_hook::fire("api_top_menu",$info['id']);?>
<?php $this->stop();?>
<?php $this->start('right-menu'); ?>
<?php $this->stop();?>
<?php $this->start('main'); ?>
<div class="col-md-2">
    <?php echo tr_hook::fire("api_list",array($info['id'],0)); ?>
</div>
<div class="col-md-10">
    <div id="editormd-view" >
        <dl class="dl-inline">
            <?php
            $obj = new doc_service_user();
            $user = $obj->getLogin();
            if($user): ?>
                <dd><a href="<?php echo $this->url('doc_controller_project@edit',$info['id']); ?>">编辑</a></dd>
            <?php endif;?>
        </dl>
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


<?php $this->layout('doc::layout') ?>
<?php $this->start('title') ?><?php echo $this->e($title) ?><?php $this->stop() ?>
<?php $this->start('left-menu'); ?>
<li class="active">
    <a href="<?php echo $this->url("doc_controller_index@index"); ?>">首页</a>
</li>
<li>
    <a href="<?php echo $this->url("doc_controller_user@index"); ?>">用户管理</a>
</li>
<?php $this->stop();?>
<?php $this->start('right-menu'); ?>
<?php $this->stop();?>
<?php $this->start('main'); ?>
<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="center-block">
            <form class="form-horizontal form" method="post" action="<?php echo $this->url("doc_controller_project@edit",$info['id']); ?>" >
                <fieldset>
                    <legend>编辑项目</legend>
                    <div class="form-group">
                        <!-- Text input-->
                        <label class="control-label col-md-2" >项目名称</label>
                        <div class="col-md-4">
                            <input type="text" placeholder="请输入项目名称" value="<?php echo $info['name'] ?>" class="form-control" name="name" data-rule="项目名称: required;name">
                        </div>
                    </div>
                    <div class="form-group">
                        <div id="editormd">
                            <textarea id="page_content" style="display:none;" tabindex="6" name="content"><?php echo $info['descr'] ?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2"></label>
                            <!-- Button -->
                            <div class="col-md-4">
                                <button class="btn btn-success ajax-post" type="submit" target-form="form">提交</button>
                            </div>
                        </div>

                </fieldset>
            </form>
        </div>
        <div class="col-md-1"></div>
    </div>
</div>
<?php $this->stop();?>
<?php $this->start('bt-static'); ?>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/marked.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/prettify.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/raphael.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/underscore.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/sequence-diagram.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/flowchart.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/lib/jquery.flowchart.min.js"></script>
<script src="<?php $this->assets('doc');?>/js/editor.md/editormd.min.js"></script>
<script type="text/javascript">
    var testEditor;

    $(function() {
        testEditor = editormd("editormd", {
            width   : "100%",
            height  : 400,
            syncScrolling : "single",
            path    : "<?php $this->assets('doc');?>/js/editor.md/lib/"
        });
    });
</script>
<?php $this->stop();?>
<?php echo $this->start('top-static') ?>
<link rel="stylesheet" href="<?php $this->assets('doc');?>/js/editor.md/css/editormd.min.css">
<?php $this->stop() ?>

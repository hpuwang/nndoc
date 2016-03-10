<?php $this->layout('doc::api_layout') ?>
<?php echo $this->start('top-static') ?>
<link rel="stylesheet" href="<?php $this->assets('doc');?>/js/editor.md/css/editormd.min.css">
<?php $this->stop() ?>
<?php $this->start('title') ?><?php echo $this->e($title) ?><?php $this->stop() ?>
<?php $this->start('brand') ?><a  class="navbar-brand" href="<?php echo $this->url("doc_controller_project@index",$info['id']); ?>"><?php echo $this->e($info['name']) ?></a><?php $this->stop() ?>
<?php $this->start('left-menu'); ?>
<?php echo tr_hook::fire("api_top_menu",$info['id']);?>
<?php $this->stop();?>
<?php $this->start('right-menu'); ?>
<?php $this->stop();?>
<?php $this->start('main'); ?>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">

            <div class="col-md-1">
            </div>
            <div class="col-md-10">
                <form class="form-horizontal form" role="form" method="post" action="<?php echo $this->url("doc_controller_project@editApi",$apiInfo['id']); ?>">
                    <legend>编辑文档</legend>

                    <div class="form-group">
                        <label class="col-md-2 control-label">标题</label>
                        <div class="col-md-2">
                            <input type="text" name="title"  value="<?php echo $apiInfo['title'];?>" class="form-control" data-rule="标题: required;title">
                        </div>
                        <label class="col-md-2 control-label">排序</label>
                        <div class="col-md-2">
                            <input type="text" name="fsort"  value="<?php echo $apiInfo['fsort'];?>" class="form-control" data-rule="排序: required;fsort">
                        </div>
                        <label class="col-md-2 control-label">组</label>
                        <div class="col-md-2">
                            <?php
                            if($group):
                                ?>
                                <select name="group" class="form-control">
                                <?php
                                foreach($group as $v):
                            ?>
                                <option <?php echo $v['id']==$apiInfo['gid']?"selected":""; ?> value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                            <?php
                            endforeach;
                            ?>
                                </select>
                            <?php
                            endif;?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <div id="editormd">
<textarea id="page_content" style="display:none;" tabindex="6" name="content"><?php echo $apiInfo['content'];?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <input type="submit" id="submit" class="btn btn-primary ajax-post" value="保存" data-loading="稍候..." target-form="form"> <input type="hidden" name="type" id="type" value="article">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-1">
            </div>
        </div>
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
            height  : 700,
            syncScrolling : "single",
            path    : "<?php $this->assets('doc');?>/js/editor.md/lib/"
        });
    });
</script>
<?php $this->stop();?>

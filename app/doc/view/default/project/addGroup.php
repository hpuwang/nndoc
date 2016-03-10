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
                <form class="form-horizontal form" role="form" method="post" action="<?php echo $this->url("doc_controller_project@addGroup",$pid); ?>">
                    <legend>创建组</legend>
                    <div class="form-group">
                        <label class="col-md-2 control-label">名称</label>
                        <div class="col-md-4">
                            <input type="text" name="name"  value="" class="form-control" data-rule="名称: required;name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">排序</label>
                        <div class="col-md-2">
                            <input type="text" name="fsort"  value="0" class="form-control" data-rule="排序: required;fsort">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <input type="submit" id="submit" class="btn btn-primary ajax-post" value="保存" data-loading="稍候..." target-form="form">
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

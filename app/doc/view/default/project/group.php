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
<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="center-block">
                <section class="cards">
                    <?php if($list):?>
                        <?php foreach($list as $k=>$v): ?>
                            <div class="col-md-4 col-sm-6 col-lg-3">
                                <a href="<?php echo $this->url("doc_controller_project@editGroup",$v['id']); ?>" class="card">
                                    <h5 class="card-heading"><?php echo $v['name']; ?></h5>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif;?>
                    <div class="col-md-4 col-sm-6 col-lg-3">
                        <a href="<?php echo $this->url("doc_controller_project@addGroup"); ?>" class="card">
                            <h5 class="card-heading">+</h5>
                        </a>
                    </div>
                </section>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>
<?php $this->stop();?>

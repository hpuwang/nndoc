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
<div class="center-block">
    <ol class="breadcrumb">
        <li class="active">项目列表</li>
    </ol>
    <section class="cards">
        <?php if($list):?>
        <?php foreach($list as $k=>$v): ?>
        <div class="col-md-4 col-sm-6 col-lg-3">
            <a href="<?php echo $this->url("doc_controller_project@index",$v['id']); ?>" class="card">
                <h5 class="card-heading"><?php echo $v['name']; ?></h5>
            </a>
        </div>
        <?php endforeach; ?>
        <?php endif;?>
        <div class="col-md-4 col-sm-6 col-lg-3">
            <a href="<?php echo $this->url("doc_controller_project@add"); ?>" class="card">
                <h5 class="card-heading">+</h5>
            </a>
        </div>
    </section>
</div>
<?php $this->stop();?>


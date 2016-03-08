<?php $this->layout('doc::layout') ?>
<?php $this->start('title') ?><?php echo $this->e($title) ?><?php $this->stop() ?>
<?php $this->start('left-menu'); ?>
<li>
    <a href="<?php echo $this->url("doc_controller_index@index"); ?>">首页</a>
</li>
<li class="active">
    <a href="<?php echo $this->url("doc_controller_user@index"); ?>">用户管理</a>
</li>
<?php $this->stop();?>
<?php $this->start('right-menu'); ?>
<?php $this->stop();?>
<?php $this->start('main'); ?>
<div class="center-block">
    <form class="form form-horizontal form" style="width: 70%;" action="<?php echo $this->url("doc_controller_user@add"); ?>" method="post">
        <fieldset>
            <legend>添加账号</legend>
            <div class="form-group ">
                <!-- Text input-->
                <label class="control-label col-md-2" >邮箱地址</label>
                <div class="col-md-4">
                    <input type="text" placeholder="请输入邮箱地址" class="form-control" name="email" data-rule="邮箱地址: required;email">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label  col-md-2" >昵称</label>
                <div class="col-md-4">
                    <input type="text" placeholder="请输入昵称" class="form-control" name="nickname" data-rule="昵称: required;nickname">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label  col-md-2" >密码</label>
                <div class="col-md-4">
                    <input type="password" placeholder="请输入密码" class="form-control" name="pwd"  data-rule="密码: required;pwd">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label  col-md-2"></label>
                <div class="col-md-4">
                    <button class="btn btn-success ajax-post" target-form="form" type="submit">提交</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<?php $this->stop();?>


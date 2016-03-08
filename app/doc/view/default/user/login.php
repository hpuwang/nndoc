<?php $this->layout('doc::layout') ?>
<?php $this->start('title') ?><?php echo $this->e($title) ?><?php $this->stop() ?>
<?php $this->start('left-menu'); ?>
<?php $this->stop();?>
<?php $this->start('main'); ?>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
<div class="center-block">
    <form class="form-horizontal login-form" method="post" action="<?php echo $this->url("doc_controller_user@login"); ?>" >
        <fieldset>
            <legend>登陆</legend>
            <div class="form-group">
                <!-- Text input-->
                <label class="control-label col-md-2" >邮箱地址</label>
                <div class="col-md-4">
                    <input type="text" placeholder="请输入邮箱地址" class="form-control" name="email" data-rule="邮箱地址: required;email">
                </div>
            </div>

            <div class="form-group">
                <!-- Text input-->
                <label class="control-label col-md-2" >密码</label>
                <div class="col-md-4">
                    <input type="password" placeholder="请输入密码" class="form-control" name="pwd"  data-rule="密码: required;pwd">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2"></label>
                <!-- Button -->
                <div class="col-md-4">
                    <button class="btn btn-success ajax-post" type="submit" target-form="login-form">提交</button>
                </div>
            </div>

        </fieldset>
    </form>
    </div>
    <div class="col-md-3"></div>
</div>

    </div>
<?php $this->stop();?>

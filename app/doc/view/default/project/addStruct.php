<?php $this->layout('doc::api_layout') ?>
<?php echo $this->start('top-static') ?>
<link rel="stylesheet" href="<?php $this->assets('doc');?>/js/editor.md/css/editormd.min.css">
<?php $this->stop() ?>
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
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">

            <div class="col-md-1">
            </div>
            <div class="col-md-10">
                <form class="form-horizontal form" role="form" method="post" action="<?php echo $this->url("doc_controller_project@addStruct",$pid); ?>">
                    <legend>创建结构体文档</legend>

                    <div class="form-group">
                        <label class="col-md-2 control-label">标题</label>
                        <div class="col-md-4">
                            <input type="text" name="title"  value="" class="form-control" data-rule="标题: required;title">
                        </div>
                        <label class="col-md-2 control-label">排序</label>
                        <div class="col-md-2">
                            <input type="text" name="fsort"  value="0" class="form-control" data-rule="排序: required;fsort">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <div id="editormd">
<textarea id="page_content" style="display:none;" tabindex="6" name="content">
-  用户表，储存用户信息

|字段|类型|空|默认|注释|
|:----    |:-------    |:--- |-- -|------      |
|uid	  |int(10)     |否	|	 |	           |
|username |varchar(20) |否	|    |	 用户名	|
|password |varchar(50) |否   |    |	 密码		 |
|name     |varchar(15) |是   |    |    昵称     |
|reg_time |int(11)     |否   | 0  |   注册时间  |

- 备注：无
</textarea>
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

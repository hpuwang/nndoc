<?php if($list):?>
<nav class="menu" data-toggle="menu" style="padding-left: 10px;">
    <ul class="nav nav-primary">
        <?php foreach($list as  $value): ?>
        <li class=" show nav-parent">
            <a href="javascript:;"><?php echo $value['name']; ?>
                <i class="icon-chevron-right nav-parent-fold-icon"></i><i class="icon-chevron-right nav-parent-fold-icon"></i>
            </a>
            <ul class="nav">
                <?php if($value['child']):?>
                    <?php foreach($value['child'] as $v):?>
                        <li <?php if($id == $v['id']) echo 'class="active"'; ?>><a href="<?php echo $this->url("doc_controller_project@view",$v['id']); ?>"><i class="icon-caret-right"></i> <?php echo $v['title']; ?></a></li>
                    <?php endforeach;?>
                <?php endif;?>
            </ul>
        </li>
        <?php endforeach;?>
    </ul>
</nav>
<?php endif;?>
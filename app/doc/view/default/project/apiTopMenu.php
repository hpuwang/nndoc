<?php
$obj = new doc_service_user();
$user = $obj->getLogin();
if($user):
    ?>
    <li>
        <a href="<?php echo $this->url("doc_controller_project@addApi",$pid);  ?>"><i class="icon-plus"></i></a>
    </li>
<li <?php $route = tr::getAppInfo();echo $route['action'] == "doc_controller_project@group"?'class="active"':"";?>>
    <a href="<?php echo $this->url("doc_controller_project@group",$pid); ?>">组管理</a>
</li>
    <?php
endif;
?>
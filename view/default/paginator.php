<?php
if($pageCount):
    $p = $current;
    $end = $p+5;
    $start = $p-5;
    $end = $end > $pageCount ? $pageCount+1 : $end;
    $start = $start < 1 ? 1 : $start;
?>
<form action="<?php echo tr::getPathInfo(); ?>" class="row" role="form" method="get" id="pager_form">
    <?php
    $param = $_POST+$_GET;
    $param = http_build_query($param);
    $param = explode("&",$param);
    $paramArr = array();
    if($param){
        foreach($param as $v){
            if(!$v) continue;
            list($k1,$v1) = explode("=",$v);
            ?>
            <input name="<?php echo urldecode($k1); ?>" value="<?php echo urldecode($v1); ?>" type="hidden">
        <?php
        }
    }
    ?>
<div class="row">
    <div class="col-xs-4"></div>
    <div class="col-xs-8">
        <div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">

            <ul class="pagination f-right">
                <?php if ($page > 1): ?>
                    <li><a href="<?php echo tr_paginator::pagiUrl(1, $pageCount, $currentUrl); ?>">首页</a></li>
                    <li  class="paginate_button"  tabindex="0" ><a href="<?php echo tr_paginator::pagiUrl($previous, $pageCount, $currentUrl); ?>">上一页</a></li>
                <?php else: ?>
                    <li class="paginate_button disabled"><a href="javascript:;">首页</a></li>
                    <li  class="paginate_button  disabled" tabindex="0" ><a href="javascript:;">上一页</a></li>
                <?php endif; ?>
                <?php if ($next > $page): ?>
                    <li class="paginate_button"><a href="<?php echo tr_paginator::pagiUrl($next, $pageCount, $currentUrl); ?>">下一页</a></li>
                    <li class="paginate_button"><a href="<?php echo tr_paginator::pagiUrl($pageCount, $pageCount, $currentUrl); ?>">末页</a></li>
                    <?php else: ?>
                    <li class="paginate_button disabled"><a href="javascript:;">下一页</a></li>
                    <li class="paginate_button disabled"><a href="javascript:;">末页</a></li>
                <?php endif; ?>
                <li class="paginate_button"><a href="javascript:;" style="padding: 3px;">
                        <input type="text" name="p" id="pagep" value="<?php echo $p; ?>" style="width: 30px;height: 20px;padding:0px;text-align: center"></a>
                </li>
                <li class="paginate_button"><a href="javascript:;" id="pageSearch">Go</a></li>
            </ul>
            <div class="f-right height30">共<strong><?php echo $totalNum ?></strong>条记录，每页
                <select name="pageSize" id="pageSize" style="height: 26px;padding:0px;">
                    <?php
                        $pageSizeArr = array(
                            "10","20","30","40","50","100","200","500","1000"
                        );
                    $pageSize = tr::getParam("pageSize");
                        foreach($pageSizeArr as $v):
                    ?>
                            <option value="<?php echo $v; ?>" <?php echo $pageSize==$v?"selected":""; ?>><?php echo $v; ?></option>
                    <?php endforeach;?>

                </select>
                条,
                <strong><?php echo $p ?>/<?php echo $pageCount ?></strong></div>
        </div>
    </div>
</div>
    </form>
    <script>
        $(function(){
            $("#pageSize").change(function(){
                $("#pager_form").submit();
            });
            $("#pageSearch").click(function(){
                if($("#pagep").val()){
                    $("#pager_form").submit();
                }
            });
        });
    </script>
<?php
endif;
?>
<?php
tr_hook::add("sys_start",function(){
    new tr_session();
    session_start();
});
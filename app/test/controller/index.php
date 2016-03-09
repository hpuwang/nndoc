<?php
/**
 * User: peter.wang
 * Url: http://2tag.cn
 * Date: 16/3/9
 * Time: 下午3:01
 */
class test_controller_index extends tr_controller{


    function index(){
        return $this->response("",tr_const::SUCCESS_OK,"success");
    }
}
<?php
/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr_validate{

    function requestVadidate(){
        $data = tr::getParam("data");
        $tokenId = tr::getParam("tokenid");
        $controller = new tr_controller();
        if(!$data){
            $controller->response(null,tr_const::ERROR_NORMAL,"data数据不能为空");
            exit;
        }

        if(!$tokenId){
            $controller->response(null,tr_const::ERROR_NORMAL,"tokenid不能为空");
            exit;
        }

        $newToken = $this->getTokenId($data);
        if($newToken == $tokenId) return true;
        $controller->response(null,tr_const::ERROR_NORMAL,"tokenid验证失败!");
        exit;
    }

    /**
     * 根据请求参数获取tokeid令牌
     * @param $data
     * @return string
     */
    function getTokenId($data){
        $authKey = tr::config()->get("app.auth_key");
        return md5(md5(stripslashes($data)).$authKey);
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 16/3/2
 * Time: 下午5:17
 */
class doc_controller_user extends tr_controller{


    function login(){
        if(isPost()){
            $email = $this->getParam("email");
            $pwd = $this->getParam("pwd");
            if(!$email) return $this->response("",tr_const::ERROR_NORMAL,"email不能为空");
            if(!$pwd) return $this->response("",tr_const::ERROR_NORMAL,"密码不能为空");
            $dao = new doc_dao_user();
            $userinfo = $dao->checkLogin($email,$pwd);
            if($userinfo){
                $service = new doc_service_user();
                $service->setLogin($userinfo);
                return $this->response("",tr_const::SUCCESS_OK,"登陆成功",url('doc_controller_index@index'));
            }
            return $this->response("",tr_const::ERROR_NORMAL,"email或者密码错误!");
        }
        $this->title = "登陆";
        $this->display();
    }

    function logout(){
        $service = new doc_service_user();
        $service->setLogin(array());
        redirect(url("doc_controller_user@login"));
    }

    function index(){
        $dao = new doc_dao_user();
        $this->list = $dao->getAll();

        $this->title = "用户管理";
        $this->display();
    }

    function add(){
        if(isPost()){
            $dao = new doc_dao_user();
            $email = $this->getParam("email");
            $nickname = $this->getParam("nickname");
            $pwd = $this->getParam("pwd");

            if(!$email) return $this->response("",tr_const::ERROR_NORMAL,"邮箱不能为空!");
            if(!$nickname) return $this->response("",tr_const::ERROR_NORMAL,"昵称不能为空!");
            if(!$pwd) return $this->response("",tr_const::ERROR_NORMAL,"密码不能为空!");

            //判断用户是否已存在
            $checkByEmail = $dao->getByEmail($email);
            if($checkByEmail)  return $this->response("",tr_const::ERROR_NORMAL,"邮箱已存在!");

            $checkByNickname = $dao->getByNickname($nickname);
            if($checkByNickname)  return $this->response("",tr_const::ERROR_NORMAL,"昵称已存在!");

            $dao->add($email,$nickname,$pwd);
            return $this->response("",tr_const::SUCCESS_OK,"操作成功",url("doc_controller_user@index"));
        }
        $this->title = "用户添加";
        $this->display();
    }

    function muser(){
        $service = new doc_service_user();
        $userInfo = $service->getLogin();
        if(isPost()){
            $pwd = $this->getParam("pwd");
            $npwd = $this->getParam("npwd");
            $nickname = $this->getParam("nickname");
            if(!$nickname) return $this->response("",tr_const::ERROR_NORMAL,"昵称不能为空!");

            $dao = new doc_dao_user();

            if($npwd){
                $userinfo = $dao->checkLogin($userInfo['email'],$pwd);
                if(!$userinfo) return $this->response("",tr_const::ERROR_NORMAL,"老密码错误!");
            }

            $checkByNickname = $dao->getByNickname($nickname,$userInfo['id']);
            if($checkByNickname)  return $this->response("",tr_const::ERROR_NORMAL,"昵称已存在!");

            $dao->mpwd($userInfo['id'],$pwd,$nickname);

            $service->setLogin(array());

            return $this->response("",tr_const::SUCCESS_OK,"操作成功",url("doc_controller_user@login"));
        }
        $this->userInfo = $userInfo;
        $this->title = "修改用户信息";
        $this->display();
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 16/3/2
 * Time: 下午5:17
 */
class doc_controller_project extends tr_controller{


    function add(){
        if(isPost()){
            $name = $this->getParam("name");
            $content = $this->getParam("content");
            if(!$name) return $this->response("",tr_const::ERROR_NORMAL,"项目名称不能为空!");
            if(!$content) return $this->response("",tr_const::ERROR_NORMAL,"项目描叙不能为空!");
            $obj = new doc_dao_project();
            $obj->add($name,$content);
            return $this->response("",tr_const::SUCCESS_OK,"操作成功",url('doc_controller_index@index'));
        }
        $this->title="添加";
        $this->display();
    }


    function edit($projectId){
        if(!$projectId) return redirect(url("doc_controller_index@index"));
        $obj = new doc_dao_project();
        $info = $obj->getById($projectId);

        if(isPost()){
            $name = $this->getParam("name");
            $content = $this->getParam("content");
            if(!$name) return $this->response("",tr_const::ERROR_NORMAL,"项目名称不能为空!");

            $obj = new doc_dao_project();
            $check = $obj->getByName($name,$projectId);
            if($check) return $this->response("",tr_const::ERROR_NORMAL,"项目名称已存在!");

            if(!$content) return $this->response("",tr_const::ERROR_NORMAL,"项目描叙不能为空!");

            $obj->edit($name,$content,$projectId);
            return $this->response("",tr_const::SUCCESS_OK,"操作成功",url('doc_controller_index@index'));
        }
        $this->title="编辑";
        $this->info = $info;
        $this->display();
    }


    function index($projectId){
        if(!$projectId) return redirect(url("doc_controller_index@index"));
        $obj = new doc_dao_project();
        $info = $obj->getById($projectId);

        $apiobj = new doc_dao_api();
        $this->apiDoc = $apiobj->getAllApi($projectId,0);
        $this->structDoc = $apiobj->getAllApi($projectId,1);

        $this->title = $info['name'];
        $this->info = $info;
        $this->display();
    }

    function view($id){
        if(!$id) return redirect(url("doc_controller_index@index"));
        $apiobj = new doc_dao_api();
        $apiInfo = $apiobj->getById($id);
        $projectId = $apiInfo['pid'];
        $obj = new doc_dao_project();
        $info = $obj->getById($projectId);

        $this->title = $apiInfo['title']."-".$info['name'];
        $this->info = $info;
        $this->apiInfo = $apiInfo;
        $this->id = $id;
        $this->display();
    }

    function addApi($projectId){
        if(!$projectId) return redirect(url("doc_controller_index@index"));

        if(isPost()){
            $title = $this->getParam("title");
            $fsort = (int) $this->getParam("fsort");
            $content = $this->getParam("content");
            $group = $this->getParam("group");

            if(!$title) return $this->response("",tr_const::ERROR_NORMAL,"标题不能为空!");
            if(!$content) return $this->response("",tr_const::ERROR_NORMAL,"文档内容不能为空!");
            if(!$group) return $this->response("",tr_const::ERROR_NORMAL,"所在组不能为空,请去添加!",url("doc_controller_project@addGroup",$projectId));

            $userService = new doc_service_user();
            $userInfo = $userService->getLogin();

            $obj = new doc_dao_api();
            $check = $obj->addDoc($title,$content,$projectId,$userInfo['id'],$group,$fsort);
            return $this->response("",tr_const::SUCCESS_OK,"操作成功!",url("doc_controller_project@view",$check));
        }

        $obj = new doc_dao_project();
        $info = $obj->getById($projectId);
        $this->title = $info['name']."-api文档添加";
        $this->info = $info;

        $groupDao = new doc_dao_group();
        $this->group = $groupDao->gets(array("pid"=>$projectId));

        $this->pid = $projectId;
        $this->display();

    }

    function addGroup($projectId){
        if(!$projectId) return redirect(url("doc_controller_index@index"));

        if(isPost()){
            $title = $this->getParam("name");
            $fsort = (int) $this->getParam("fsort");

            if(!$title) return $this->response("",tr_const::ERROR_NORMAL,"名称不能为空!");
            $obj = new doc_dao_group();
            $check = $obj->addGroup($title,$projectId,$fsort);
            return $this->response("",tr_const::SUCCESS_OK,"操作成功!",url("doc_controller_project@group",$projectId));
        }

        $obj = new doc_dao_project();
        $info = $obj->getById($projectId);
        $this->title = $info['name'];
        $this->info = $info;
        $this->pid = $projectId;
        $this->display();
    }


    function editGroup($id){
        if(!$id) return redirect(url("doc_controller_index@index"));
        $obj = new doc_dao_group();
        $ginfo = $obj->getById($id);
        $projectId = $ginfo['pid'];
        if(isPost()){
            $name = $this->getParam("name");
            $fsort = (int) $this->getParam("fsort");

            if(!$name) return $this->response("",tr_const::ERROR_NORMAL,"名称不能为空!");
            $obj = new doc_dao_group();
            $obj->editGroup($name,$fsort,$id);
            return $this->response("",tr_const::SUCCESS_OK,"操作成功!",url("doc_controller_project@group",$projectId));
        }

        $obj = new doc_dao_project();
        $this->info = $obj->getById($projectId);

        $this->title = $ginfo['name'];
        $this->ginfo = $ginfo;
        $this->pid = $projectId;
        $this->display();
    }

    function editApi($id){
        if(!$id) return redirect(url("doc_controller_index@index"));
        $apiobj = new doc_dao_api();
        $apiInfo = $apiobj->getById($id);
        $this->apiInfo = $apiInfo;
        if(isPost()){
            $title = $this->getParam("title");
            $fsort = (int) $this->getParam("fsort");
            $content = $this->getParam("content");
            $group = $this->getParam("group");

            if(!$title) return $this->response("",tr_const::ERROR_NORMAL,"标题不能为空!");
            if(!$content) return $this->response("",tr_const::ERROR_NORMAL,"文档内容不能为空!");


            $obj = new doc_dao_api();
            $obj->editDoc($title,$content,$fsort,$group,$id);
            return $this->response("",tr_const::SUCCESS_OK,"操作成功!",url("doc_controller_project@view",$id));
        }
        $projectId = $apiInfo['pid'];
        $obj = new doc_dao_project();
        $this->info = $obj->getById($projectId);
        $this->title = $apiInfo['title'];

        $groupDao = new doc_dao_group();
        $this->group = $groupDao->gets(array("pid"=>$projectId));

        $this->display();
    }

    function delApi($id){
        if(!$id) return redirect(url("doc_controller_index@index"));
        $apiobj = new doc_dao_api();
        $apiInfo = $apiobj->getById($id);
        if(!$apiInfo) redirect(url("doc_controller_index@index"));
        $apiobj->delete(array("id"=>$id));
        redirect(url("doc_controller_project@index",$apiInfo['pid']));
    }


    function group($projectId){
        if(!$projectId) return redirect(url("doc_controller_index@index"));
        $obj = new doc_dao_project();
        $this->info = $obj->getById($projectId);
        $dao = new doc_dao_group();
        $this->list = $dao->gets(array("pid"=>$projectId),"fsort asc");
        $this->title = "组管理";
        $this->display();
    }

}
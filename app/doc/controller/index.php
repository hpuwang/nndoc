<?php
class doc_controller_index extends tr_controller{

    function index(){
        $obj = new doc_dao_project();
        $service = new doc_service_user();
        $this->list = $obj->getAll();
        $this->user = $service->getLogin();
        $this->title="首页";
        $this->display();
    }

}
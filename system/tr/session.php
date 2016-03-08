<?php
/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr_session {
    public function __construct() {
        $param = tr::config()->get("app.memcached");
        if(!$param) return false;
        if(class_exists("Memcached") || class_exists("Memcache")) {
            session_set_save_handler(
                array($this, "open"), array($this, "close"), array($this, "read"), array($this, "write"), array($this, "destroy"), array($this, "gc")
            );
        }
    }

    public function open($savePath, $sessionName) {
        return true;
    }

    public function close() {
        return true;
    }

    public function read($id) {
        return tr_mcache::mget($id);
    }

    public function write($id, $data) {
        $liftTime = tr::config()->get("app.session.timeout");
        return tr_mcache::mset($id, $data, $liftTime);
    }

    public function destroy($id) {
        return tr_mcache::mdelete($id);
    }

    public function gc($maxlifetime) {

    }

}
<?php
/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr_redis{
    public function redisObj($ident) {
        $hasher = new Flexihash();
        // allocate nodes
        $nodes = tr::config()->get("app.redis");
        if(!$nodes) return false;
        foreach ($nodes as $key => $node) {
            $hasher->addTarget($key);
        }
        $k =$hasher->lookup($ident);

        // build the connection
        $R = new Redis();
        $R->connect($nodes[$k]['host'], $nodes[$k]['port'], $nodes[$k]['weight']);
        return $R;
    }


    public function get($id) {

        $versionTmp = tr::getVersion();
        if($versionTmp) {
            list($app, $apiVersion, $from, $secondFrom) = $versionTmp;
            $id = $app . $from . $secondFrom . $id;
        }
        $obj=$this->redisObj($id);
        if(!$obj) return false;
        $value = $obj->get($id);
        $result = $value ? unserialize($value) : $value;
        return $result;
    }

    public function set($id, $data,$expoire=3600) {
        $versionTmp = tr::getVersion();
        if($versionTmp) {
            list($app, $apiVersion, $from, $secondFrom) = $versionTmp;
            $id = $app . $from . $secondFrom . $id;
        }
        $liftTime = $expoire?$expoire:tr::config()->appGet("app.session.timeout");
        $result = $data ? serialize($data) : $data;
        $obj=$this->redisObj($id);
        if(!$obj) return false;
        $obj->set($id,$result);
        $obj->expire($id,$liftTime);
        return true;
    }

    public function del($id) {
        $versionTmp = tr::getVersion();
        if($versionTmp) {
            list($app, $apiVersion, $from, $secondFrom) = $versionTmp;
            $id = $app . $from . $secondFrom . $id;
        }
        $obj=$this->redisObj($id);
        if(!$obj) return false;
        return $obj->del($id);
    }
}
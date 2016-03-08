<?php
/**
 * memcache 类库
 */
/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr_mcache{
    protected static $_instance = null;
    static function init(){
        if(self::$_instance) return self::$_instance;
        $param = tr::config()->get("app.memcached");
        if(!$param) return false;
        if(class_exists("Memcached")){
            $memcached = new Memcached;
            if(is_array($param)){
                foreach($param as $v){
                    $memcached->addServer(
                        $v['hostname'], $v['port'], $v['weight']
                    );
                }
            }else{
                $memcached->addServer(
                    $param['hostname'], $param['port'], $param['weight']
                );
            }
            $memcached->setOption(Memcached::OPT_DISTRIBUTION, Memcached::DISTRIBUTION_CONSISTENT);
            $memcached->setOption(Memcached::OPT_HASH, Memcached::HASH_CRC);
        }else{
            $memcached = new Memcache();
            if(is_array($param)) {
                foreach ($param as $name => $cache_server) {
                    $memcached->connect($cache_server['hostname'], $cache_server['port'], $cache_server['weight']);
                    break;
                }
            }else{
                $memcached->connect(
                    $param['hostname'], $param['port'], $param['weight']
                );
            }
        }
        self::$_instance = $memcached;
        return self::$_instance;
    }

    static function set($k,$v,$expireTime=60){
        if(!$v) return true;
        $obj = self::init();
        if(!$obj) return false;
        if(class_exists("Memcached")){
//            echo "set|";
            $obj->set($k,$v,$expireTime);
        }else{
            $obj->set($k,$v,false,$expireTime);
        }
        return true;
    }

    static function get($k){
//        echo "get|";
        $obj = self::init();
        if(!$obj) return false;
        return $obj->get($k);
    }

    static function delete($k){
        $obj = self::init();
        if(!$obj) return false;
        return $obj->delete($k);
    }

    /**
     * 负载均衡添加ip字段均衡到key memcache
     */
    static function mem_ip($numServer=3){
        $ip = $_SERVER["SERVER_ADDR"]?$_SERVER["SERVER_ADDR"]:"127.0.0.1";
        $ip = ip2long($ip);
        $num = $ip % $numServer;
        $str = '_'.$num;
        return $str;
    }

    /**
     * 初始化key
     * @param $k
     * @return string
     */
    static function initKey($k){
        $app = tr::getReset();
        if($app){
            $versionTmp = tr::getVersion();
            if($versionTmp) {
                list($app, $apiVersion, $from, $secondFrom) = $versionTmp;
                $k = $app . $from . $secondFrom . $k;
            }
        }else{
            $k=  $app .  $k;
        }
        $k = $k.self::mem_ip();
        return $k;
    }

    /**
     * 分布式set
     * @param $k
     * @param $v
     * @param int $expireTime
     * @return bool
     */
    static function mset($k,$v,$expireTime=60){
        if(!$v) return true;
        $k = self::initKey($k);
        $v = serialize($v);
        self::set($k,$v,$expireTime);
        return true;
    }

    /**
     * 分布式get
     * @param $k
     * @return array|bool|mixed|string
     */
    static function mget($k){
        $k = self::initKey($k);
        $result = self::get($k);
        $result = $result?unserialize($result):$result;
        return $result;
    }

    /**
     * 分布式delete
     * @param $k
     * @return bool
     */
    static function mdelete($k){
        $k = self::initKey($k);
        self::delete($k);
        return true;
    }

}
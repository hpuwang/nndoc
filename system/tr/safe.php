<?php
/**
 * 防攻击
 */
/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr_safe{

  static function doSafe(){
      $requestUri = $_SERVER['REQUEST_URI'];
      $param = parse_url($requestUri);
      $path = isset($param['path']) ? trim(strtolower($param['path']),"/") : "";
      list($app,$apiVersion,$from,$secondFrom)=tr::getVersion();
      $path_infoNew = str_replace($app."/".$from.$secondFrom.$apiVersion,"",$path);
      if (class_exists('memcached') || class_exists('memcache')) {
          $token = session_id();
          $microtime = microtime(true);
          $microtime = substr($microtime,0,-1);
          $key = "safe_token_check_{$path_infoNew}_{$microtime}_{$token}";
          if(class_exists('memcached')){
              if(!tr_mcache::init()) return true;
              if (!tr_mcache::init()->add($key, 1, 1)) {
                  self::showEndMsg();
              }
          } elseif(class_exists('memcache')){
              if(!tr_mcache::init()) return true;
              if (!tr_mcache::init()->add($key, 1,0, 1)) {
                  self::showEndMsg();
              }
          }
      }
  }

    static function showEndMsg($msg=''){
        $obj = new tr_controller();
        $obj->response(null,1000,$msg?$msg:"网络超时，请稍后重试!");
        exit;
    }

    static function getInitData($config=''){
        $requestUri = $_SERVER['REQUEST_URI'];
        $param = parse_url($requestUri);
        $path = isset($param['path']) ? trim(strtolower($param['path']),"/") : "";
        list($app,$apiVersion,$from,$secondFrom)=tr::getVersion();
        $path_infoNew = str_replace($app."/".$from.$secondFrom.$apiVersion."/","",$path);
        if(!$config){
            $lock = tr::config()->get("app.lock");
            if(isset($lock[$app])){
                $config = $lock[$app];
            }else{
                return false;
            }
            if(isset($config[$path_infoNew])){
                $rconfig = $config[$path_infoNew];
            }else{
                return false;
            }
        }
        return array($app,$path_infoNew,$rconfig);
    }

    static function apiLock($config=''){
        self::checkLock();
        $rs = self::getInitData($config);
        if(!$rs) return true;
        list($app,$path_infoNew,$rconfig)=$rs;
        $ip = get_client_ip();
        $key = "api_lock_sts_".$ip."_".$app."_".$path_infoNew;
        $cache = tr_mcache::get($key);

        $diffTime = $rconfig['diff_time'];
        $diffNum =  $rconfig['diff_num'];
        $locakTime= $rconfig['lock_time'];
        if($cache){
            $userCount = $cache['user'];
            if($userCount>=$diffNum){
                $key = "api_lock_".$ip."_".$app."_".$path_infoNew;
                tr_mcache::set($key,1,$locakTime);
                return true;
            }
            $time = $cache['time'];
            if((time()-$time)>$diffTime){
                $data=array();
                $data['time'] = time();
                $data['user'] =  1;
                tr_mcache::set($key,$data,$diffTime);
                return ;
            }
            $cache['user'] = $cache['user']+1;
            tr_mcache::set($key,$cache,$diffTime);
        }else{
            $data=array();
            $data['time'] = time();
            $data['user'] = 1;
            tr_mcache::set($key,$data,$diffTime);
        }
    }

    /**
     * 检查锁定情况
     * @param string $ip
     * @return array|mixed|string|void
     */
    static function checkLock($ip=''){
        $rs = self::getInitData();
        if(!$rs) return true;
        list($app,$path_infoNew,$rconfig)=$rs;
        $ip = $ip?$ip:get_client_ip();
        $key = "api_lock_".$ip."_".$app."_".$path_infoNew;
        $cache =  tr_mcache::get($key);
        if($cache) return self::showEndMsg("因为安全问题,你的ip已被锁定，请".ceil($rconfig['lock_time']/3600)."小时后再来访问!");
        return $cache;
    }
}
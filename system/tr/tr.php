<?php
/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr{
    public static $currentApp = null;
    protected static $_versionInstance = null;
    protected static $_routeData = array();
    static function getParam($str = null,$default=null)
    {
        parse_str(file_get_contents('php://input'), $data);
        add_s($data);
        $data = $data? $data:array();
        $all = array_merge($_POST, $data);
        $all = array_merge($_GET, $all);
        if (!$str) {
            return $all;
        }
        $result = isset($all[$str]) ? $all[$str] : $default;
        return is_string($result)?stripslashes($result):$result;
    }

    static function config()
    {
        return tr_config::config();
    }

    static function getPath(){
        $path_info = '/';
        if (! empty($_SERVER['PATH_INFO'])) {
            $path_info = $_SERVER['PATH_INFO'];
        } elseif (! empty($_SERVER['ORIG_PATH_INFO']) && $_SERVER['ORIG_PATH_INFO'] !== '/index.php') {
            $path_info = $_SERVER['ORIG_PATH_INFO'];
        } else {
            if (! empty($_SERVER['REQUEST_URI'])) {
                $path_info = (strpos($_SERVER['REQUEST_URI'], '?') > 0) ? strstr($_SERVER['REQUEST_URI'], '?', true) : $_SERVER['REQUEST_URI'];
            }
        }
        return $path_info;
    }

    static function getReset(){
        $resetApps = tr::config()->get("app.reset_apps");
        $pathInfo = tr::getPath();
        $pathInfoArr = explode("/",trim($pathInfo,"/"));
        if(count($pathInfoArr)<3) return "";
        $app1 = $pathInfoArr[0];
        if(in_array($app1,$resetApps)) return $app1;
        $app1 = $pathInfoArr[0]."/".$pathInfoArr[1];
        if(in_array($app1,$resetApps)) return $app1;
        return "";
    }

   static function getApp(){
       return isset(self::$currentApp['app'])?self::$currentApp['app']:"";
    }

    static function getAppInfo(){
        return self::$currentApp;
    }

    static function parsePath(){
        $path_info = self::getPath();
        $path_info = trim($path_info,'/');
        $app = tr::getReset();
        $path_info = preg_replace('/^'.str_replace("/","\/",$app).'/',"",$path_info);
        $pathinfoArr = explode("/",trim($path_info,"/"));
        $version = array_shift($pathinfoArr);
        return array($app,$version);
    }

    static function getVersion(){
        if(self::$_versionInstance) return self::$_versionInstance;
        list($app,$apiVersion) = self::parsePath();
        $resetRoute = tr::config()->get("app.reset_apps");
        //非reset 访问
        if(!in_array($app,$resetRoute)) return false;
        preg_match("/([a-zA-Z]+)/i",$apiVersion,$match);
        if(!isset($match[1])){
            tr_hook::fire('404');
            return false;
        }
        $from = substr($match[1],0,1);
        $secondFrom =  strlen($match[1])>1?substr($match[1],1):"";
        $apiVersion = str_replace($match[1],"",$apiVersion);
        $apiVersion = !strstr($apiVersion,".")?$apiVersion.".0":$apiVersion;
        if($apiVersion <0 ){
            tr_hook::fire('404');
            return false;
        }
		$apiVersion = intval($apiVersion);
        self::$_versionInstance  = array($app,$apiVersion,$from,$secondFrom);
        return self::$_versionInstance;
    }

    static function lang($str,$app="",$replace=array()){
        $lang = isset($_COOKIE['lang'])?$_COOKIE['lang']:"zhcn";
        $lang = "lang/".$lang;
        $str  = $lang."/".$str;
        $str = self::config()->get($str,$app);
        if($replace && is_array($replace)){
            foreach($replace as $k=>$v){
                $str = str_replace("{#".$k."#}",$v,$str);
            }
        }
        return $str;
    }

    static function isdebug()
    {
        return tr::config()->get("app.debug");
    }

    static function getUrlAction($url){
        $allRouteData = tr_route::$allRouteData;
        $rs = isset($allRouteData[$url])?$allRouteData[$url]:null;
        if($rs) return $rs;
        if($allRouteData && !$rs){
            $tokens = array(
                ':string' => '([a-zA-Z]+)',
                ':number' => '([0-9]+)',
                ':p' => '([0-9]+)',
                ':alpha'  => '([a-zA-Z0-9-_]+)'
            );

            foreach($allRouteData as $k=>$v){
                $pattern = strtr($k, $tokens);
                preg_match('#^/?' . $pattern . '/?$#', $url, $matches);
                if($matches){
                    return $v;
                    break;
                }
            }
        }
        return null;
    }

    static function url($action,$string=array()){
        $allRouteData = tr_route::$allRouteData;
        $rsUrl = self::$_routeData;
        if($rsUrl){
            if(isset($rsUrl[$action])){
                return self::urlMatch($rsUrl[$action],$string);
            }
        }
        $rsUrl=array();
        if($allRouteData){
            foreach($allRouteData as $k=>$v){
                $rsUrl[$v[0]] = $k;
            }
        }

        self::$_routeData = $rsUrl;
        if(isset($rsUrl[$action])){
            return self::urlMatch($rsUrl[$action],$string);
        }
        return "#";
    }

    static function urlMatch($pre,$params=array()){
        $isWrite = tr::config()->get("app.is_write");
        $strp = "";
        if(!$isWrite) $strp = "/index.php";
        if($params){
            $pre = str_replace(":string",":",$pre);
            $pre = str_replace(":number",":",$pre);
            $pre = str_replace(":p",":",$pre);
            $pre = str_replace(":alpha",":",$pre);

            if(is_string($params)){
                $check= strpos($pre,":");
                if($check!==false){
                    $pre = substr_replace($pre,$params,$check);
                }
                return $strp.$pre;
            }
            foreach ($params as $k => $v) {
                $check= strpos($pre,":");
                if($check!==false){
                    $pre = substr_replace($pre,$v,$check);
                }else{
                    break;
                }
            }
            return $strp.$pre;
        }
        return $strp.$pre;
    }


   static function getPathInfo(){
        $path_info = '/';

        if (! empty($_SERVER['PATH_INFO'])) {
            $path_info = $_SERVER['PATH_INFO'];
        } elseif (! empty($_SERVER['ORIG_PATH_INFO']) && $_SERVER['ORIG_PATH_INFO'] !== '/index.php') {
            $path_info = $_SERVER['ORIG_PATH_INFO'];
        } else {
            if (! empty($_SERVER['REQUEST_URI'])) {
                $path_info = (strpos($_SERVER['REQUEST_URI'], '?') > 0) ? strstr($_SERVER['REQUEST_URI'], '?', true) : $_SERVER['REQUEST_URI'];
            }
        }
       $path_info = str_replace("index.php","",$path_info);
        return $path_info;
    }

    static function runtime(){
        $now = getmicrotime();
        $time = $now-tr_init::$elapsedTime;
        return number_format($time,3);
    }
}
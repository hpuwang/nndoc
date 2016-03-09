<?php
/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr_route{
    public static $allRouteData=null;
    public static function serve($routes,$routesTmp=array())
    {
        if($routesTmp){
            self::$allRouteData = $routesTmp;
        } else{
            self::$allRouteData = $routes;
        }
        tr_hook::fire('before_request', compact('routes'));
        $request_method = strtolower($_SERVER['REQUEST_METHOD']);

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

        $discovered_handler = null;
        $regex_matches = array();
        list($discovered_handler,$regex_matches)=self::match($routes,$path_info);

        $result = null;
        $handler_instance = null;

        //如果没有匹配
        if(!$discovered_handler && $routesTmp){
            list($app,$apiVersion,$from,$secondFrom)=tr::getVersion();
            $path_infoNew = str_replace("/".$app."/".$from.$secondFrom.$apiVersion,"",$path_info);
            list($discovered_handler,$regex_matches)=self::match($routesTmp,$path_infoNew);
        }

        if(!$discovered_handler){
            $discovered_handler = isset(self::$allRouteData['/'])?self::$allRouteData['/']:"";
        }
        if(!$discovered_handler){
            tr_hook::fire("404");
            return ;
        }

        $discovered_handler_new = isset($discovered_handler[0])?$discovered_handler[0]:"";
        $app = end($discovered_handler);
        if(count($discovered_handler)>2){
            $actionName = isset($discovered_handler[1])?$discovered_handler[1]:"";
            $groupName = isset($discovered_handler[2])?$discovered_handler[2]:"";
        }
        tr::$currentApp = array("action"=>$discovered_handler_new,"actionName"=>$actionName,"groupName"=>$groupName,"app"=>$app);

        $result = array($discovered_handler,$request_method,$regex_matches);
        tr_log::debug("route_match:".json_encode($result));
        return $result;
    }

  static function run($discovered_handler,$request_method,$regex_matches){

      $handler_instance = null;
        if ($discovered_handler) {
            $discovered_handler_new = isset($discovered_handler[0])?$discovered_handler[0]:"";
            $app = end($discovered_handler);
            if(count($discovered_handler)>2){
                $actionName = isset($discovered_handler[1])?$discovered_handler[1]:"";
                $groupName = isset($discovered_handler[2])?$discovered_handler[2]:"";
            }
            tr::$currentApp = array("action"=>$discovered_handler_new,"actionName"=>$actionName,"groupName"=>$groupName,"app"=>$app);
            if(stristr($discovered_handler_new,"@")){
                list($classNameTmp,$request_method) = explode("@",$discovered_handler_new);
                $className = self::getRealClassName($classNameTmp,$request_method);
                tr_log::debug("controller:".$className);
                $handler_instance = new $className();
            }else{
                if (is_string($discovered_handler_new)) {
                    $className = self::getRealClassName($discovered_handler_new,$request_method);
                    tr_log::debug("controller:".$className);
                    $handler_instance = new $className();
                } elseif (is_callable($discovered_handler_new)) {
                    $className = self::getRealClassName($discovered_handler_new,$request_method);
                    tr_log::debug("controller:".$className);
                    $handler_instance = new $className();
                }
            }
        }
//        d($handler_instance);
        if ($handler_instance) {
            unset($regex_matches[0]);
            if (method_exists($handler_instance, $request_method)) {
                tr_hook::fire('before_handler', compact('routes', 'discovered_handler', 'request_method', 'regex_matches'));
                tr_log::debug("action:".$request_method);
                $result = call_user_func_array(array($handler_instance, $request_method), $regex_matches);
                tr_hook::fire('after_handler', compact('routes', 'discovered_handler', 'request_method', 'regex_matches', 'result'));
            } else {
                tr_hook::fire("404");
                return ;
            }
        } else {
            tr_hook::fire("404");
            return ;
        }
        tr_hook::fire('after_request', compact('routes', 'discovered_handler', 'request_method', 'regex_matches', 'result'));
    }

    private static function getRealClassName($className,$request_method){
        $classArr = explode("_",$className);
        $app = tr::getApp();
        $classArr[1]=$classArr[1]."_extc";
        $className2 = implode("_",$classArr);
        if(class_exists($className2)){
            if(method_exists(new $className2() ,$request_method)) return $className2;
        }
        return $className;
    }

    private static function match($routes,$path_info){
        $discovered_handler = null;
        $regex_matches = array();
//        print_r($routes);
//        print_r($path_info);
        if (isset($routes[$path_info])) {
            $discovered_handler = $routes[$path_info];
        } elseif ($routes) {
            $tokens = array(
                ':string' => '([a-zA-Z]+)',
                ':number' => '([0-9]+)',
                ':p' => '([0-9]+)',
                ':alpha'  => '([a-zA-Z0-9-_]+)'
            );
            foreach ($routes as $pattern => $handler_name) {
                $pattern = strtr($pattern, $tokens);
                if (preg_match('#^/?' . $pattern . '/?$#', $path_info, $matches)) {
                    $discovered_handler = $handler_name;
                    $regex_matches = $matches;
                    break;
                }
            }
        }
        return array($discovered_handler,$regex_matches);
    }


    static function routePre()
    {
        $routeConfigTmp = array();

        $apps = tr::config()->get("app.apps");
        $resetApps = tr::config()->get("app.reset_apps");

        $reset = tr::getReset();
        foreach($apps as $apk){
            $fpath = ROOT_PATH."/app/".$apk."/config/route.php";
            if(is_file($fpath)){
                $arrTmp = include($fpath);
                if($arrTmp){
                    foreach($arrTmp as $kt=>$vt){
                        if($reset != $apk) array_push($vt,$apk);
                        $arrTmp[$kt] =$vt;
                    }
                }
                $routeConfigTmp[$apk] = $arrTmp;
            }
        }

        if(!$routeConfigTmp) {
            tr_hook::fire("404");
            return false;
        }
        tr_log::debug("is_reset:".$reset);
        tr_log::debug("route:".json_encode($routeConfigTmp));

        //如果不是reset 匹配
        if(!$reset){
            //非reset 合并
            $routeApps = self::diffConfigPath($resetApps,$apps,$routeConfigTmp);
           return self::serve($routeApps);
        }
        $versionRs = tr::getVersion();
        list($app,$version,$from,$secondFrom) = $versionRs;
        $routeConfig = isset($routeConfigTmp[$app])?$routeConfigTmp[$app]:null;

        if(!$routeConfig){
            tr_hook::fire("404");
            return false;
        }

        if($routeConfig){
            $newRouteConfig = array();
            $newRouteConfigTmp = array();
            arsort($routeConfig);
            foreach($routeConfig as $k=>$v){
                if($v){
                    if($version<$k) continue;
                    foreach($v as $kEnd=>$vEnd){
                        $routetmp = $app."/".$from.$secondFrom.$k."/".$kEnd;
                        $routetmp = str_replace("//",'/',$routetmp);
                        array_push($vEnd,$reset);
                        $newRouteConfig[$routetmp]=$vEnd;
                        $newRouteConfigTmp[$kEnd]=$vEnd;
                    }
                }
            }
//            print_r($newRouteConfig);
//            print_r($newRouteConfigTmp);
            return self::serve($newRouteConfig,$newRouteConfigTmp);
        }else{
            tr_hook::fire("404");
            return false;
        }
    }

    static function diffConfigPath($resetApps,$apps,$routeConfigTmp){
        if($resetApps){
            $diffApps = array_diff($apps,$resetApps);
        }else{
            $diffApps = $apps;
        }
        $routeApps = array();
        if($diffApps){
            foreach($diffApps as $v){
                if(!$routeConfigTmp[$v]) continue;
                $routeApps = array_merge($routeConfigTmp[$v],$routeApps);
            }
        }
        return $routeApps;
    }

}
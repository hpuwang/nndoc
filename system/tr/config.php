<?php

/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr_config
{
    protected static $_instance = null;
    public static $config = array();

    /**
     * @return tr_config
     */
    public static function config()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }

    function appGet($str,$app=''){
        if(!$str) return false;
        if(!$app) $app = tr::getApp();
        $path = ROOT_PATH."/app/".$app."/config";
        $path = str_replace("//","/",$path);
        return $this->getValue($str, $path);
    }

    function get($str, $app = ""){
        $result = array();
        $value = $this->appGet($str,$app);
        $all = $this->getValue($str);

        if(is_array($value) && is_array($all)){
            if($value && $all){
                $result = array_merge_recursive($all,$value);
            }else{
                if($value)  $result =  $value;
                if($all)  $result =  $all;
            }
        }else{
            if($value)  return $value;
            if($all)  return $all;
        }
        return $result;
    }

     function getValue($str, $path = "")
    {
        if (!$str) return null;
        $configPath = ROOT_PATH . "/config";
        if ($path) $configPath = $path;
        $configPathEnv = "";
        if (ENVIRONMENT) $configPathEnv = $configPath . "/" . ENVIRONMENT;

        $arr = array();
        $configs = array();
        $configPathTmp = "";
        if (strstr($str, ".")) {
            $arr = explode(".", $str);
            $configPathTmp = array_shift($arr);
            $configPathTmp .= ".php";
        } else {
            $configPathTmp .= $str . ".php";
        }

        $envPath = $configPathEnv . "/" . $configPathTmp;
        $commonPath = $configPath . "/" . $configPathTmp;
        $md5envPath = md5($envPath . $str);
        $md5commonPath = md5($commonPath . $str);
        if (isset(self::$config[$md5envPath])) return self::$config[$md5envPath];
        if (isset(self::$config[$md5commonPath])) return self::$config[$md5commonPath];

        if (ENVIRONMENT && is_file($envPath)) {
            $configs = include($envPath);
            $configs = $configs?$configs:null;
            if ($arr) {
                foreach ($arr as $v) {
                    if (!$configs) break;
                    $configs = isset($configs[$v]) ? $configs[$v] : null;
                }
            }

            if ($configs !== null) {
                if (strstr($str, ".")) {
                    self::$config[$md5envPath] = $configs;
                    return $configs;
                } else {
                    $configs2 = include($commonPath);
                    $rs = array_merge($configs2, $configs);
                    self::$config[$md5commonPath] = $rs;
                    return $rs;
                }
            }
        }

        if(!is_file($commonPath)) return null;
        $configs = include($commonPath);
        if ($arr) {
            foreach ($arr as $v) {
                if (!$configs) break;
                $configs = isset($configs[$v]) ? $configs[$v] : null;
            }
        }
        self::$config[$md5commonPath] = $configs;
        return $configs;
    }


    function writePhp($path, $phpcode)
    {
        $phpstr = '<?php' . PHP_EOL . $phpcode . PHP_EOL;
        if(!is_writable($path) && is_file($path)) throwException($path."文件不可写");
        file_put_contents($path, $phpstr, LOCK_EX);
        chmod($path,0777);
    }
}
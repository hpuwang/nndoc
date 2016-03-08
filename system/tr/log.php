<?php
/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr_log{
    static function debug($data, $key=null){
        static $pid;
        $data = is_string($data)?$data:json_encode($data);
        !$pid && $pid=function_exists('posix_getpid')?posix_getpid():mt_rand(1,9999);
        $ip = self::get_server_ip();
        $time = tr::runtime();
        $newData = "[".$ip."]"."[".$pid."]"."[".date('Y-m-d H:i:s')."][cost:".$time."]".$data;
        mydebug::add(urldecode($newData),$key);
    }

    static function getDebug(){
        return mydebug::getAll();
    }

    static function log($data,$filename='log.log'){
        list($app) =  tr::getVersion();
        $configPath = tr::config()->get("app.logPath");
        $path = $configPath."/".$app."/".ENVIRONMENT."/".date('Y-m-d')."/".date('Y-m-d-h')."/";
        if(! is_dir($path)) mkdir($path, 0777, true);
        $filePath = $path.$filename;
        static $pid;
        $data = is_string($data)?$data:json_encode($data);
        !$pid && $pid=function_exists('posix_getpid')?posix_getpid():mt_rand(1,9999);
        $ip = self::get_server_ip();
        $time = tr::runtime();
        $newData = "[".$ip."]"."[".$pid."]"."[".date('Y-m-d H:i:s')."][cost:".$time."]".$data;
        file_put_contents($filePath, $newData."\r\n", FILE_APPEND);
    }

    static function get_server_ip(){
        if(!empty($_SERVER['SERVER_ADDR']))
            return $_SERVER['SERVER_ADDR'];
        return gethostbyname($_SERVER['HOSTNAME']);
    }

    static function getJsDebug($isShow=1){
        $debug = self::getDebug();
        $str = "<script>";
        if($debug){
            foreach($debug as $k=>$v){
                if(is_string($v)){
                    $v = str_replace("\n","",$v);
                    $v = addslashes($v);
                    $str .= "console.log('[".$k."]-".$v."');\r\n";
                }else{
                    $v= json_encode($v);
                    $v = str_replace("\n","",$v);
                    $v = addslashes($v);
                    $str .= "console.log('[".$k."]-".$v."');\r\n";
                }
            }
        }
        $str .="</script>";
        if($isShow){
            echo $str;
        }else{
            return $str;
        }
    }
}

class mydebug{

    /**
     * @var array $arrError 数据数组，先进后出
     */
    static private $trackData = array();

    /**
     * 增加数据
     * @static
     * @param mixed $data 错误数据
     */
    static public function add($data, $key=null)
    {
        if($key === null)
        {
            self::$trackData[] = $data;
            if(count(self::$trackData) > 30){
                array_shift(self::$trackData);
            }
        }else{
            self::$trackData[$key] = $data;
        }
    }

    /**
     * 清除数据数组
     * @static
     */
    static public function clear()
    {
        self::$trackData = array();
    }

    /**
     * 当前是否有数据
     * @static
     * @return bool 返回是否有数据
     */
    static public function has($key='')
    {
        if(!$key){
            return (count(self::$trackData) > 0);
        }
        return isset(self::$trackData[$key]) && self::$trackData[$key] ? true : false;
    }

    /**
     * 获取最后一个数据
     * @static
     * @return mixed 返回最后一个数据
     */
    static public function last($key='')
    {
        if(!$key) {
            return end(self::$trackData);
        }
        return isset(self::$trackData[$key]) ? self::$trackData[$key] : null;
    }

    /**
     * 获取所有错误
     * @static
     * @return array 数据数组
     */
    static public function getAll()
    {
        return self::$trackData;
    }

}
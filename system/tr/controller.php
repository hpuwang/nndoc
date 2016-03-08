<?php
/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr_controller{
    public static $templates=null;
    private  static $_variable = array();

    static function getParam($str = null,$default=null)
    {
        return tr::getParam($str,$default);
    }

    static function display($path=array(),$param=array()){
        if(!$path){
            $class = get_called_class();
            $method = get_called_method($class);
            if($class){
                preg_match("/.*?_controller_(.*)/i",$class,$match);
                if(!isset($match[1])){
                    throw_new_exception("controller格式错误!");
                    return ;
                }
                $classArr = explode("_",$match[1]);
                $app = tr::getApp();
                $path = $app."::".implode("/",$classArr)."/".$method;
                tr_log::debug($path);
            }
        }
        $param = array_merge(self::$_variable,$param);
        echo self::tpl()->render($path, $param);
        $debug = self::getParam("debug");
        $debugValue=tr::config()->get("app.debug_value");
        if($debug==$debugValue){
            tr_log::getJsDebug();
        }
    }


    static function render($path=array(),$param=array()){
        return self::tpl()->render($path, $param);
    }


    static function tpl(){
        if(self::$templates) return self::$templates;
        $app = tr::getApp();
        $skin = tr::config()->get("app.tpl_skin");
        $templates = new League\Plates\Engine(ROOT_PATH.'/view/'.$skin);
        if($app){
            $templates->addFolder($app, ROOT_PATH.'/app/'.$app.'/view/'.$skin.'');
            $templates->loadExtension(new League\Plates\Extension\Asset(ROOT_PATH.'/app/'.$app.'/extensions'));
        }
        self::$templates = $templates;
        tr_hook::fire("tpl_add_ext",$templates);
        return $templates;
    }

    function errorReturn($info=null){
        return tr_error::returnError($info);
    }

    function __set($key,$value){
        self::$_variable[$key]=$value;
    }

    function response($bodyData = null, $errorCode = null, $errorDescr = null,$url=null) {
        $this->cross_domain();
        @header("Content-type: application/json; charset=utf-8");
        $request = $this->getParam();
        $callback = isset($request['callback']) ? $request['callback'] : '';
        $runTime = getmicrotime() - tr_init::$elapsedTime;
        $data = array();
        $data['elapsedTime'] = number_format($runTime, 4);
        $data['errorCode'] = $errorCode ? $errorCode : tr_const::SUCCESS_OK;
        $data['errorDesc'] = $errorDescr ? $errorDescr : "";
        $data['url'] = $url ? $url : "";
        $data['body'] = $bodyData ? $bodyData : (object) null;
        $json = json_encode($data);
        $json = str_replace(':null', ':""', $json);
        $debug = $this->getData("debug");
        $debugValue=tr::config()->get("app.debug_value");
        if($debug==$debugValue){
            $obj = new tr_log();
            $debug = $obj->getDebug();
            print_r($debug);
        }
        if ($callback) {
            echo $callback . '(' . $json . ');';
        } else {
            echo $json;
        }
    }

     function cross_domain()
    {
        if (empty($_SERVER['HTTP_ORIGIN']))
        {
            return;
        }

        $wrap_header['origin'] = 'Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN'];
        $wrap_header['cred'] = 'Access-Control-Allow-Credentials:true';
        $wrap_header['allow_methods'] = 'Access-Control-Allow-Methods: POST, GET, OPTIONS';
        $wrap_header['allow_header'] = 'Access-Control-Allow-Headers: accept, origin, withcredentials, content-type,urlEncodeCharset, Accept-Charset, sid, th5_sid';

        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS')
        {
            $wrap_header['cache'] = "Access-Control-Max-Age:86400";
        }

        foreach ($wrap_header as $key => $header_line)
        {
            @header($header_line);
        }

        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS')
        {
            exit;
        }
    }

    function getRequestBody($str){
        $body = $this->getData("body");
        if(!$body) return null;
        return isset($body[$str])?$body[$str]:null;
    }

    function getData($str){
        $request = self::getParam("data");
        if(!$request) return null;
        $request = stripslashes($request);
        $jsonRequest = json_decode($request, TRUE);
        if(!$jsonRequest) return null;
        return isset($jsonRequest[$str])?$jsonRequest[$str]:null;
    }

}
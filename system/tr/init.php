<?php
include_once ROOT_PATH . "/function.php";
include_once ROOT_PATH . "/system/tr/config.php";
include_once ROOT_PATH . "/system/tr/tr.php";
$namespaces = tr_config::config()->get("app.namespaces");
$appsnamespaces = tr_config::config()->get("app.apps_namespaces");

spl_autoload_register(array(new tr_init, 'loader'));
//模版基本方法
tr_hook::add("tpl_add_ext",function($templates){
    $templates->registerFunction('assets',function($app=''){
        return tr_tpl::assets($app);
    });
    $templates->registerFunction('g_assets',function($app=''){
        return tr_tpl::g_assets($app);
    });
    $templates->registerFunction('lang',function($str,$app='',$replace=array()){
        return tr_tpl::lang($str,$app,$replace);
    });
    $templates->registerFunction('url',function($action,$string=''){
        return tr_tpl::url($action,$string);
    });
    $templates->registerFunction('style',function($url){
        return tr_tpl::style($url);
    });
    $templates->registerFunction('script',function($url){
        return tr_tpl::script($url);
    });
});


/**  * @author peter.wang  * @url http://2tag.cn  */ class tr_init
{
    private static $_instance = null;
    public static $elapsedTime=0;
    public static $routeData=null;


    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {

    }

    function create()
    {
        tr_init::$elapsedTime = getmicrotime();
        if(!$this->isdebug()){
            error_reporting(0);
        }else{
            error_reporting(E_ALL);
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
        }
        tr_log::debug("debug:".$this->isdebug());
        tr_hook::add("404", function() {
            $controller = new tr_controller();
            $path = tr::config()->get("app.error404_tpl");
            $controller->display($path);
            exit;
        });
            $this->route();
            $this->hook();
            tr_hook::fire("sys_start");
            $this->initialize();
            tr_hook::fire("route_start");
            list($discovered_handler,$request_method,$regex_matches) = self::$routeData;
            $this->cronJob();
            tr_route::run($discovered_handler,$request_method,$regex_matches);
            tr_hook::fire("sys_end");
    }

    function cronJob(){
        //crontab 执行
        $randJob = tr::config()->get("app.rand_job");
        $randJob = $randJob?floor(1/$randJob):10;
        $rand = mt_rand(1,$randJob);
        if($rand === 1){
            tr_log::debug("cronjob");
            tr_hook::fire("job");
        }
    }


    function initialize()
    {
        //输入过滤
        if (!get_magic_quotes_gpc()) {
            !empty($_POST) && add_s($_POST);
            !empty($_GET) && add_s($_GET);
            !empty($_COOKIE) && add_s($_COOKIE);
            !empty($_FILES) && add_s($_FILES);
        }
    }


    function hook()
    {
        include_once ROOT_PATH . "/app/hook.php";
        $app = tr::getApp();
        $path = ROOT_PATH."/app/".$app."/hook.php";
        if(is_file($path)){
            include_once  $path;
        }
        $apps = tr_config::config()->get("app.apps");
        tr_log::debug("apps:".json_encode($apps));
        if($apps){
            foreach($apps as $app){
                $path = ROOT_PATH."/app/".$app."/global_hook.php";
                if(is_file($path)){
                    include_once  $path;
                }
            }
        }
    }

    function route()
    {
        self::$routeData = tr_route::routePre();
    }



    function isdebug()
    {
        return tr::isdebug();
    }

    function loader($className)
    {
        global $namespaces, $appsnamespaces;
        $libpath = ROOT_PATH . "/system";
        $apppath = ROOT_PATH . "/app";
        $extpath = ROOT_PATH . "/ext";
        if (strstr($className, '_')) {
            $pathArr = explode('_', $className);

            if ($pathArr) {
                if (in_array($pathArr[0], $namespaces)) {
                    $path = "";
                    foreach ($pathArr as $v) {
                        $path .= $v . "/";
                    }
                    $path = trim($path, "/");
                    $pathTmp = $libpath . "/" . $path . ".php";
                    if(is_file($pathTmp)) {
                        require_once $pathTmp;
                    }
                }
                if (in_array($pathArr[0], $appsnamespaces)) {
                    $path = "";
                    foreach ($pathArr as $v) {
                        $path .= $v . "/";
                    }
                    $path = trim($path, "/");
                    $pathTmp = $apppath . "/" . $path . ".php";
                    if(is_file($pathTmp)){
                        require_once $pathTmp;
                    }
                }
                if($pathArr[0] == 'ext'){
                    unset($pathArr[0]);
                    $path = "";
                    foreach ($pathArr as $v) {
                        $path .= $v . "/";
                    }
                    $path = trim($path, "/");
                    $pathTmp = $extpath . "/" . $path . ".php";

                    if(is_file($pathTmp)){
                        require_once $pathTmp;
                    }
                }
            }
        } else {
            if (!in_array($className, $namespaces)) return true;
            $pathTmp = $libpath . "/" . $className . "/" . $className . ".php";
            if(is_file($pathTmp)) {
                require_once $pathTmp;
            }
        }
    }
}

class dao extends tr_db{
    static $tablename='';

    static function table($table){
        self::$tablename = $table;
        return new self;
    }
}

register_shutdown_function(function(){
    $error = error_get_last();
    if ($error) {
        // 崩溃错误，记录日志
        tr_log::log("type:".$error['type'].",msg:".$error['message'].",file:".$error['file'].",line:".$error['line']."");
    }
});

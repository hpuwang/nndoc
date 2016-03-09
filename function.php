<?php
if (!function_exists('get_called_method')) {
    function get_called_method()
    {
        $backtrace = debug_backtrace();
        array_shift($backtrace);
        return $backtrace[1]['function'];
    }
}

function d($value){
    print_r($value);
    exit;
}

function pr($value){
    print_r($value);
}

function add_s(&$array)
{
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if (!is_array($value)) {
                $array[$key] = addslashes($value);
            } else {
                add_s($array[$key]);
            }
        }
    } else {
        $array = addslashes($array);
    }
}

/**
 * 获取主域名
 */
function getMainDomain()
{
    $host = strtolower($_SERVER['HTTP_HOST']);
    if (strpos($host, '/') !== false) {
        $parse = @parse_url($host);
        $host = $parse ['host'];
    }
    $topleveldomaindb = array('com', 'edu', 'gov', 'int', 'mil', 'net', 'org', 'biz', 'info', 'pro', 'name', 'museum', 'coop', 'aero', 'xxx', 'idv', 'mobi', 'cc', 'me');
    $str = '';
    foreach ($topleveldomaindb as $v) {
        $str .= ($str ? '|' : '') . $v;
    }

    $matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";
    if (preg_match("/" . $matchstr . "/ies", $host, $matchs)) {
        $domain = $matchs ['0'];
    } else {
        $domain = $host;
    }
    return $domain;
}

function removeXss($val)
{
    $val = stripslashes($val);
    $result = dealWithXss($val);
    return $result;
}

function dealWithXss($html, $allow_tag = array(), $allow_tag_attr = array())
{
    if (!$allow_tag) {
        $allowStr = "p,strong,a,em,table,td,tr,h1,h2,h3,h4,h5,hr,br,u,ul,ol,li,center,code,div,font,blockquote,small,caption,img,span,strike,sup,sub,b,dl,dt,dd,embed,object,param,pre,tbody";
        $allow_tag = explode(',', $allowStr);
    }
    if (!$allow_tag_attr) {
        $allow_tag_attr = array(
            '*' => array(
                'style' => '/.*/i',
                'alt' => '/.*/i',
                'width' => '/^[\w_-]+$/i',
                'height' => '/^[\w_-]+$/i',
                'class' => '/.*/i',
                'name' => '/^.*$/i',
                'value' => '/.*/i',
            ),
            "object" => array("data" => '/.*/i',
            ),
            "embed" => array(
                "type" => '/.*/i',
                'src' => '/.*/i',
            ),
            "font" => array(
                "color" => '/^[\w_-]+$/i',
                "size" => '/^[\w_-]+$/i',
            ),
            'a' => array(
                'href' => '/.*/i',
                'title' => '/.*/i',
                'target' => '/^[\w_-]+$/i',
            ),
            'img' => array(
                'src' => '/.*/i',
            ),
        );
    }
    //匹配出所有尖括号包含的字符串
    preg_match_all('/<[^>]*>/s', $html, $matches);

    if ($matches[0]) {
        $tags = $matches[0];

        foreach ($tags as $tag_k => $tag) {

            //匹配出标签名 比如 a, br, html, li, script
            preg_match_all('/^<\s{0,}\/{0,}\s{0,}([\w]+)/i', $tag, $tag_name);
            $tags[$tag_k] = array('name' => isset($tag_name[1][0])?$tag_name[1][0]:"", 'html' => $tag);
            if ($tag_name && in_array($tags[$tag_k]['name'], $allow_tag)) {

                //匹配出含等于号的属性，注，当前版本不支持readonly等无等于号的属性
                preg_match_all('/\s{0,}([a-z]+)\s{0,}=\s{0,}["\']{0,}([^\'"]+)["\']{0,}[^>]/i', $tag, $tag_matches);
                if ($tag_matches[0]) {
                    $tags[$tag_k]['attr'] = $tag_matches;
                    foreach ($tags[$tag_k]['attr'][1] as $k => $v) {
                        $attr = $tags[$tag_k]['attr'][1][$k];
                        $value = $tags[$tag_k]['attr'][2][$k];
                        $preg_attr_all = isset($allow_tag_attr['*'][$attr]) ? $allow_tag_attr['*'][$attr] : "";
                        $preg_attr = isset($allow_tag_attr[$tags[$tag_k]['name']][$attr]) ? $allow_tag_attr[$tags[$tag_k]['name']][$attr] : "";

                        //判断该属性是否允许，如不允许，则unset。
                        if (($preg_attr && preg_match($preg_attr, $value)) || ($preg_attr_all && preg_match($preg_attr_all, $value))) {
                            $tags[$tag_k]['attr'][0][$k] = "{$attr}='{$value}'";
                        } else {
                            unset($tags[$tag_k]['attr'][0][$k]);
                        }
                    }
                    $tags[$tag_k]['replace'] = '<' . $tags[$tag_k]['name'];
                    if (is_array($tags[$tag_k]['attr'][0])) $tags[$tag_k]['replace'] .= ' ' . implode(' ', $tags[$tag_k]['attr'][0]);
                    $tags[$tag_k]['replace'] .= '>';
                } else {
                    $tags[$tag_k]['replace'] = $tags[$tag_k]['html'];
                }
            } else {
                $tags[$tag_k]['replace'] = htmlentities($tags[$tag_k]['html']);
            }
            $search[$tag_k] = $tags[$tag_k]['html'];
            $replace[$tag_k] = $tags[$tag_k]['replace'];
        }
        $html = str_replace($search, $replace, $html);
    }
    return $html;
}

function getRequestType()
{
    $accept = $_SERVER['HTTP_ACCEPT'];
    $types = explode(',', $accept);
    if (in_array("text/html", $types)) {
        return "html";
    } elseif (in_array("application/json", $types)) {
        return "json";
    } elseif (in_array("application/xml", $types)) {
        return "xml";
    } else {
        return "unknow";
    }
}

function substrtxt($string, $length = 80, $etc = '')
{
    $str = mb_substr($string, 0, $length, "UTF-8");
    return $etc ? $str . $etc : $str;
}

/**
 *
 * 加密解密
 * @param string $string
 * @param string $operation
 * @param string $key
 * @param string $expiry
 */
function authcode($string, $key = '', $operation = 'DECODE', $expiry = 0)
{
    $ckey_length = 4;
    if ($key == "") return false;
    $key = md5($key ? $key : "ad^%FFGFFFF");
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}

/**
 *
 * 模拟file_get_contents
 * @param string $durl
 * @param int $timeOut
 * @param array $proxyArr array("127.0.0.1:8080", "user:pwd")
 */
function curlGetContents($durl, $timeOut = 0, $proxyArr = array())
{
    if (!$durl) return false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $durl);
    if ($timeOut) {
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
    }
    curl_setopt($ch, CURLOPT_HEADER, 0);
    if ($proxyArr) {
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        curl_setopt($ch, CURLOPT_PROXY, $proxyArr[0]);
        if (isset($proxyArr[1])) {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyArr[1]);
        }
    }
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $r = curl_exec($ch);
    if (!$r) return false;
    return $r;
}

function getmicrotime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function getOlineIp()
{
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    preg_match("/[\d\.]{7,15}/", $onlineip, $ipmatches);
    $onlineip = $ipmatches[0] ? $ipmatches[0] : 'unknown';
    return $onlineip;
}

#是否是ajax请求，jquery有效
function isAjax()
{
    $tag = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : "";
    return $tag == 'xmlhttprequest' ? true : false;
}

/**
 *
 * 是否提交
 */
function isPost()
{
    return strtolower($_SERVER['REQUEST_METHOD']) == "post" ? true : false;
}


//自动转码
function safeEncoding($string, $outEncoding = 'UTF-8')
{
    $encoding = "UTF-8";
    for ($i = 0; $i < strlen($string); $i++) {
        if (ord($string{$i}) < 128)
            continue;

        if ((ord($string{$i}) & 224) == 224) {
            //第一个字节判断通过
            $char = $string{++$i};
            if ((ord($char) & 128) == 128) {
                //第二个字节判断通过
                $char = $string{++$i};
                if ((ord($char) & 128) == 128) {
                    $encoding = "UTF-8";
                    break;
                }
            }
        }
        if ((ord($string{$i}) & 192) == 192) {
            //第一个字节判断通过
            $char = $string{++$i};
            if ((ord($char) & 128) == 128) {
                //第二个字节判断通过
                $encoding = "GBK";
                break;
            }
        }
    }
    if (strtoupper($encoding) == strtoupper($outEncoding))
        return $string;
    else
        return iconv($encoding, $outEncoding . "//ignore", $string);
}

/**
 * php 切割html字符串 自动配完整标签
 *
 * @param $s 字符串
 * @param $zi 长度
 * @param $ne 没有结束符的html标签
 */
function htmlCut($s, $zi, $ne = ',br,hr,input,img,')
{
    $s = stripslashes($s);
    $s = preg_replace('/\s{2,}/', ' ', $s);
    $os = preg_split('/<[\S\s]+?>/', $s);
    preg_match_all('/<[\S\s]+?>/', $s, $or);
    if (!$or[0]) return mb_substr($s, 0, $zi, "UTF-8");
    $s = '';
    $tag = array();
    $n = 0;
    $m = count($or[0]) - 1;
    foreach ($os as $k => $v) {
        $n = $k > $m ? $m : $k;
        if ($v != '' && $v != ' ') {
            $l = strlen($v);
            for ($i = 0; $i < $l; $i++) {
                if (ord($v[$i]) > 127) {
                    $s .= $v[$i] . $v[++$i] . $v[++$i];
                } else {
                    $s .= $v[$i];
                }
                $zi--;
                if ($zi < 1) {
                    break 2;
                }
            }
        }

        preg_match('/<\/?([^\s>]+)[\s>]{1}/', $or[0][$n], $t);
        $s .= $or[0][$n];
        if (strpos($ne, ',' . strtolower($t[1]) . ',') === false && $t[1] != '' && $t[1] != ' ') {
            $n = array_search('</' . $t[1] . '>', $tag);
            if ($n !== false) {
                unset($tag[$n]);
            } else {
                array_unshift($tag, '</' . $t[1] . '>');
            }
        }
    }
    return $s . implode('', $tag);
}

/**
 *
 * 几小时前
 * @param int $time
 * @return string
 */
function qtime($time)
{
    if($time =='0000-00-00 00:00:00') return "";
    if (is_string($time)) $time = strtotime($time);
    $limit = time() - $time;

    if ($limit < 60)
        $time = "{$limit}秒前";
    if ($limit >= 60 && $limit < 3600) {
        $i = floor($limit / 60);
        $_i = $limit % 60;
        $s = $_i;
        $time = "{$i}分{$s}秒前";
    }
    if ($limit >= 3600 && $limit < 3600 * 24) {
        $h = floor($limit / 3600);
        $_h = $limit % 3600;
        $i = ceil($_h / 60);
        $time = "{$h}小时{$i}分前";
    }
    if ($limit >= (3600 * 24) && $limit < (3600 * 24 * 30)) {
        $d = floor($limit / (3600 * 24));
        $time = "{$d}天前";
    }
    if ($limit >= (3600 * 24 * 30)) {
        $time = gmdate('Y-m-d H:i', $time);
    }
    return $time;
}

/**
 *
 * 来源页
 * @param string $default
 */
function getRef($default = "")
{
    return isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : $default;
}


/**
 * 取出html图片地址
 * @param string $content
 */
function getImgPath($content)
{
    //取出图片路径
    $content = str_replace("alt=\"\"", "", $content);
    $content = str_replace("alt=\'\'", "", $content);
    $content = str_replace("alt=", "", $content);
    $content = str_replace("alt", "", $content);
    preg_match_all("/<img.*?src\s*=\s*.*?([^\"'>]+\.(gif|jpg|jpeg|bmp|png))/i", $content, $match);
    $result = isset($match[1]) ? $match[1] : array();
    if ($result) return $result;
    preg_match_all("/<img.*?src=[\"|\'|\s]?(.*?)[\"|\'|\s]/i", $content, $match1);
    return isset($match1[1]) ? $match1[1] : array();
}

function check_email($email)
{
    $regular = '/^[a-z0-9]([a-z0-9\\.]*[-_]{0,4}?[a-z0-9-_\\.]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+([\\.][\\w_-]+){1,5}$/i';   //update by xuewen 2014-11-28
    if(strpos($email,'@') AND preg_match($regular,$email)){
        return true;
    }else{
        return false;
    }
}

function get_client_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if(isset($_SERVER['HTTP_X_REAL_IP']) &&$_SERVER['HTTP_X_REAL_IP'] ){//nginx 代理模式下，获取客户端真实IP
        $ip=$_SERVER['HTTP_X_REAL_IP'];
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {//客户端的ip
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {//浏览当前页面的用户计算机的网关
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];//浏览当前页面的用户计算机的ip地址
    }else{
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

function get_server_ip(){
    if(!empty($_SERVER['SERVER_ADDR']))
        return $_SERVER['SERVER_ADDR'];
    return gethostbyname(isset($_SERVER['HOSTNAME'])?$_SERVER['HOSTNAME']:"unknow");
}

function throwException($msg){
    throw new Exception($msg);
}

function script($url)
{
    $url = trim($url, "/");
    $dir = dirname($_SERVER['PHP_SELF']);
    $dirArr = explode("index.php",$dir);
    $dir = array_shift($dirArr);
    $dir = str_replace("\\", "/", $dir);
    $path = $dir ."/asset/" . $url;
    $path = str_replace("//", "/", $path);
    $v = tr::config()->get("app.static_version");
//    return $path . "?v=" . $v . ".js" ;
    return "<script src=\"" . $path . "?v=" . $v . ".js\"></script>" . PHP_EOL;
}

function style($url)
{
    $url = trim($url, "/");
    $dir = dirname($_SERVER['PHP_SELF']);
    $dirArr = explode("index.php",$dir);
    $dir = array_shift($dirArr);
    $dir = str_replace("\\", "/", $dir);
    $path = $dir ."/asset/" . $url;
    $path = str_replace("//", "/", $path);
    $v = tr::config()->get("app.static_version");
//    return $path . "?v=" . $v. ".css" ;
    return "<link rel=\"stylesheet\" type='text/css' href=\"" . $path . "?v=" . $v . ".css\"/>" . PHP_EOL;
}

function isCli(){
    return substr(PHP_SAPI_NAME(),0,3) == 'cli';
}

function throw_new_exception($msg=''){
    if(!$msg) $msg= tr::lang("sys.param_is_null");
    if(!is_string($msg)) $msg = json_encode($msg);
    throw new Exception($msg);
}

function error_return($msg=''){
    if(!$msg) $msg= tr::lang("sys.param_is_null");
    return tr_error::returnError($msg);
}

function has_error(){
    return tr_error::lastError();
}

function redirect($url){
    if(!$url) throw_new_exception(tr::lang("sys.no_redirect_url"));
    header("Location:".$url);
    exit;
}

function show_msg($msg='',$redirect=1){
    $controller = new tr_controller();
    $path = tr::config()->get("app.msg_tpl");
    $isNum = is_numeric($redirect);
    $controller->display($path,array("msg"=>$msg,"redirect"=>$redirect,"isNum"=>$isNum));exit;
}

function my_md5($str){
    if(!$str) return throw_new_exception();
    $salt = tr::config()->get("app.auth_key");
    return md5($salt.$str);
}

#复制目录
function xCopy($source, $destination, $child = 1)
{
    if (!is_dir($source)) {
        return false;
    }
    if (!is_dir($destination)) {
        mkdir($destination, 0777, true);
    }

    $handle = dir($source);
    while ($entry = $handle->read()) {
        if (($entry != ".") && ($entry != "..")) {
            if (is_dir($source . "/" . $entry)) {
                if ($child)
                    xCopy($source . "/" . $entry, $destination . "/" . $entry, $child);
            } else {
                copy($source . "/" . $entry, $destination . "/" . $entry);
            }
        }
    }
    return true;
}

function url($action,$string=''){
    return tr::url($action,$string);
}

//md5目录
function md5_folder ($dir) {
    if (!is_dir($dir)) {
        return false;
    }

    $filemd5s = array();
    $d = dir($dir);

    while (false !== ($entry = $d->read())) {
        if ($entry != '.' && $entry != '..' && $entry != '.svn') {
            if (is_dir($dir.'/'.$entry)) {
                $filemd5s[] = md5_folder($dir.'/'.$entry);
            } else {
                $filemd5s[] = md5_file($dir.'/'.$entry);
            }
        }
    }
    $d->close();
    return md5(implode('', $filemd5s));
}
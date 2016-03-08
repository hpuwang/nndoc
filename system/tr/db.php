<?php
/**
 * @author peter.wang
 * @url http://2tag.cn
 */
class tr_db{

    static $hasTran = 0;//是否有事务
    private static $_total = 0;
    static $_sql = array();
    static $instance = array();
    static $sqlinstance=array();

  static function getTableName()
    {
        $className = get_called_class();
        return self::getConfig("prefix") . $className::$tablename;
    }

    static function getDbAdapterConfig(){
        $className = get_called_class();
        if(array_key_exists("dbAdapter",get_class_vars($className))){
            $dbAdapterName = $className::$dbAdapter;
            $result =  $dbAdapterName?$dbAdapterName:"default";
        }else{
            $result = "default";
        }
       return $result;
    }

    static function getConfig($key){
        $keyTmp = "app.db.".self::getDbAdapterConfig();
        $dbConfig = tr::config()->get($keyTmp);
        return isset($dbConfig[$key])?$dbConfig[$key]:null;
    }

    static function getAdapter($dnType = 0,$userStatic=1)
    {
        if(isset(self::$instance[$dnType]) && self::$instance[$dnType] && $userStatic) return self::$instance[$dnType];

        $dbh = null;
        $slaveDBH = null;
        $keyTmp = "app.db.".self::getDbAdapterConfig();
        $dbconfig = tr::config()->get($keyTmp);
        if (isset($dbconfig['master'])) {
            $masterConfig = $dbconfig['master'];
            $dbh = new PDO('mysql:host=' . $masterConfig['host'] . ';port=' . $masterConfig['port'] . ';dbname=' . $masterConfig['db_name'] . '', $masterConfig['user'], $masterConfig['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        }

        if (isset($dbconfig['slave'])) {
            $masterConfig = $dbconfig['slave'];
            $slaveDBH = new PDO('mysql:host=' . $masterConfig['host'] . ';port=' . $masterConfig['port'] . ';dbname=' . $masterConfig['db_name'] . '', $masterConfig['user'], $masterConfig['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        }

        if ($dnType == 0) {
            $connection = $dbh;
        } else {
            $connection = $slaveDBH ? $slaveDBH : $dbh;
        }

        if(!$connection){
            throw new Exception("数据连接失败,connect-config:".self::getDbAdapterConfig());
        }

        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        self::$instance[$dnType] = $connection;
        tr_log::debug("db_object:".json_encode(self::$instance));
        return $connection;
    }


    /**
     * 启动事务
     * @access public
     * @return void
     */
    static function startTrans()
    {
        if (!self::$hasTran) {
            self::exec("BEGIN", null, 0);
        }
        self::$hasTran++;
        return true;
    }

    /**
     * 提交事务
     * @access public
     * @return boolean
     */
    static function commit()
    {
        if (self::$hasTran) self::$hasTran--;
        if (!self::$hasTran) {
            return self::exec("COMMIT", null, 0);
        }
    }

    /**
     * 事务回滚
     * @access public
     * @return boolean
     */
    static function rollback()
    {
        self::$hasTran = 0;
        return self::exec("ROLLBACK", null, 0);
    }

    /**
     * invokes the read/write connection
     */
    static function insert(array $data)
    {
        $tableName = self::getTableName();

        if (self::getConfig("auto_time")) {
            $data['ctime'] = date('Y-m-d H:i:s');
            $data['mtime'] = date('Y-m-d H:i:s');
        }

        $keys = array_keys($data);
        $sql = "INSERT INTO " . $tableName . "(" . implode(',', $keys) . ")
                VALUES(:" . implode(':, :', $keys) . ":) ";
        foreach ($data as $k => $v) {
            $v = self::getAdapter()->quote($v);
            $sql = str_replace(":" . $k.":", $v, $sql);
        }

        self::getAdapter()->exec($sql);
        $lastInsertId = self::getAdapter()->lastInsertId();
        self::$_sql[] = $sql;
        tr_log::debug($lastInsertId,"insert result");
        return $lastInsertId;
    }

    /**
     * 根据条件，有则更新，无则插入
     */
    static function insertIf(array $data, array $where)
    {
        if (!$data || !$where) return false;
        $tableName = self::getTableName();
        $whereSql = self::parseWhere($where);
        if (self::getConfig("auto_time")) {
            $data['ctime'] = date('Y-m-d H:i:s');
            $data['mtime'] = date('Y-m-d H:i:s');
        }
        $keys = array_keys($data);
        $sql = "INSERT INTO " . $tableName . "(" . implode(',', $keys) . ")
                VALUES(:" . implode(':, :', $keys) . ":)
                ON DUPLICATE KEY UPDATE " . $whereSql;

        foreach ($data as $k => $v) {
            $v = self::getAdapter()->quote($v);
            $sql = str_replace(":" . $k.":", $v, $sql);
        }

        self::getAdapter()->exec($sql);
        self::$_sql[] = $sql;
        
        return true;
    }

    /**
     * invokes the read/write connection
     */
    static function delete($where = array())
    {
        $tableName = self::getTableName();

        $whereStr = self::parseWhere($where);
        $whereStr = $whereStr ? " WHERE " . $whereStr : "";

        $sql = "DELETE FROM `" . $tableName . "`" . $whereStr;
        $return = self::getAdapter()->exec($sql);
        self::$_sql[] = $sql;
        
        return $return;
    }


    /**
     * Invokes the read/write connection
     */
    static function update(array $data, $where = array())
    {
        $tableName = self::getTableName();

        $whereStr = self::parseWhere($where);
        $whereStr = $whereStr ? " WHERE " . $whereStr : "";

        if (self::getConfig("auto_time")) {
            $data['mtime'] = date('Y-m-d H:i:s');
        }

        $sql = "UPDATE `" . $tableName . "` SET ";
        foreach ($data as $k => $v) {
            $v = self::getAdapter()->quote($v);
            $sql .= $k . "=" . $v . ",";
        }
        $sql = rtrim($sql, ',');
        $sql .= $whereStr;
        self::$_sql[] = $sql;
        
        return self::getAdapter()->exec($sql);
    }

    /**
     *
     * 自增
     * @param string $field
     * @param array $where
     */
    static function inCrease($field, $where = array(), $number = 1)
    {
        $tableName = self::getTableName();

        $whereSql = self::parseWhere($where);
        $whereSql = $whereSql ? " WHERE " . $whereSql : "";

        $sql = "UPDATE `{$tableName}` SET {$field} = {$field} +{$number} " . $whereSql;
//        
        self::getAdapter()->exec($sql);
        self::$_sql[] = $sql;
        
        return true;
    }


    /**
     *
     * 自减
     * @param string $field
     * @param array $where
     */
    static function deCrement($field, $where = array(), $number = 1)
    {
        $tableName = self::getTableName();

        $whereSql = self::parseWhere($where);
        $whereSql = $whereSql ? " WHERE " . $whereSql : "";

        $sql = "UPDATE `{$tableName}` SET {$field} = {$field} - {$number} " . $whereSql;
        self::getAdapter()->exec($sql);
        self::$_sql[] = $sql;
        
        return true;
    }

    /**
     *
     * 更新，插入，删除sql执行,只返回受影响的行数
     * @param unknown_type $sql
     * @param unknown_type $data
     */
  static function exec($sql, $data = array(), $check = 1)
    {
        if (empty($sql)) return false;
        if ($check) {
            if (!(stristr($sql, "insert") || stristr($sql, "update") || stristr($sql, "delete") || stristr($sql, "drop"))) throw new Exception("此函数只能用于添加，更新，删除数据库操作");
        }
        if ($data) {
            foreach ($data as $k => $v) {
                $v = self::getAdapter()->quote($v);
                $sql = str_replace(":" . $k, $v, $sql);
            }
        }
//    	echo $sql;exit;
        $result = self::getAdapter()->exec($sql);
        if(!$result){
            if (self::getAdapter()->errorCode() != '00000'){
                $error = self::getAdapter()->errorInfo();
                throw new Exception('错误: ['.$error['1'].'] '.$error['2']);
            }
        }
        self::$_sql[] = $sql;
        
        return $result;
    }


    static function query($sql){
        if (empty($sql)) return false;
        $result =  self::getAdapter(1)->query($sql);
        if(!$result){
            if (self::getAdapter(1)->errorCode() != '00000'){
                $error = self::getAdapter(1)->errorInfo();
                throw new Exception('错误: ['.$error['1'].'] '.$error['2']."\r\n<br>sql:".$sql."");
            }
        }
        self::$_sql[] = $sql;
        
        return $result;
    }

    /**
     * sql语句获取数据库数据
     * @param string $sql
     * @param array $data
     */
static function selectRow($sql, $data = array())
    {
        if (empty($sql)) return false;
        if (stristr($sql, "insert") || stristr($sql, "update") || stristr($sql, "delete")) throw new Exception("此函数不能用于添加，更新，删除数据库操作");
        if ($data) {
            foreach ($data as $k => $v) {
                $v = self::getAdapter(1)->quote($v);
                $sql = str_replace(":" . $k, $v, $sql);
            }
        }
        return self::query($sql)->fetch();
    }

    /**
     *
     * 用sql语句获取所有
     * @param string $sql
     * @param array $data
     * @param $returnCount //SQL_CALC_FOUND_ROWS
     */
    static function selectAll($sql, $data = array(), $returnCount = false)
    {
        if (empty($sql)) return false;
        if (stristr($sql, "insert") || stristr($sql, "update") || stristr($sql, "delete")) throw new Exception("此函数不能用于添加，更新，删除数据库操作");
        if ($data) {
            foreach ($data as $k => $v) {
                $v = self::getAdapter(1)->quote($v);
                $sql = str_replace(":" . $k, $v, $sql);
            }
        }
//
        $rs = self::query($sql)->fetchAll();
        if ($returnCount) {
            $sqlCount = 'SELECT FOUND_ROWS() as cnt';
            $rsCount = self::query($sqlCount)->fetch();
            self::$_total = $rsCount['cnt'];
        }
        return $rs;
    }

    static function getTotal()
    {
        return self::$_total;
    }

    //获取单个字段的数据
    static function selectAllByField($sql, $field = "id", $data = array(), $returnCount = false)
    {
        $list = self::selectAll($sql, $data, $returnCount);
        $rs = array();
        if ($list) {
            foreach ($list as $v) {
                $rs[] = $v[$field];
            }
        }
        return $rs;
    }


    /**
     *
     * 解析where数据
     * @param array $where
     */
    static  function parseWhere($where = array())
    {

        if (count($where) < 1) return "";
        $whereSql = "";

        foreach ($where as $k => $v) {
            $param = $k . " = ? ";
            $andOR = "AND";
            $more=0;
            if (is_array($v)) {
                if(isset($v[0][0]) && $v[0][0] && (is_array($v[0]))){
                    if($v){
                        foreach($v as $kk=>$vv){
                            $whereSql .= self::joinSql($vv,$k,$andOR);
                        }
                    }
                    $more=1;
                }else{
                    list($sign, $vl) = $v;

                    if(isset($v[2]) && $v[2]) $andOR = $v[2];
                    if($vl !==''){
                        $param = $k . " " . $sign . " ? ";
                        $sign = strtolower($sign);
                        if ($sign == 'in') {
                            $param = $k . " " . $sign . " (?) ";
                        }
                        $v = $vl;
                    }else{
                        $param = $k . " " . $sign . " ";
                    }
                    $andOR = strtoupper($andOR);
                }
            }
            if(!$more){
                $whereSql .= " $andOR (" . self::quoteInto($param, $v).")";
            }
        }
        $whereSql = trim($whereSql);
        $whereSql = trim($whereSql,"OR");
        $whereSql = trim($whereSql,"AND");
        return $whereSql;
    }

    static function joinSql($v,$feild,$andOR){
        list($sign, $vl) = $v;

        if(isset($v[2]) && $v[2]) $andOR = $v[2];
        if($vl !==''){
            $param = $feild . " " . $sign . " ? ";
            $sign = strtolower($sign);
            if ($sign == 'in') {
                $param = $feild . " " . $sign . " (?) ";
            }
            $v = $vl;
        }else{
            $param = $feild . " " . $sign . " ";
        }
        $andOR = strtoupper($andOR);
        $whereSql = " $andOR (" . self::quoteInto($param, $v).")";
        return $whereSql;
    }

    static function quoteInto($param, $v)
    {
        if(is_array($v)){
            $str = "";
            foreach($v as $v1){
                if(!is_array($v1)){
                    $str .= self::getAdapter(1)->quote($v1).",";
                }
            }
            $str = trim($str,",");
            return str_replace('?', $str, $param);
        }else{
            return str_replace('?', self::getAdapter(1)->quote($v), $param);
        }
    }

    /**
     * 根据条件获取多个
     * @param array $where
     */
    static  function gets($where = array(), $orderBy = "", $limit = "", $offset = "", $groupBy = "", $returnCount = false)
    {
        $whereSql = self::parseWhere($where);

        $whereSql = $whereSql ? " WHERE " . $whereSql : "";
        $tableName = self::getTableName();
        $orderBySql = "";
        $groupBySql = "";
        $limitSql = "";
        if ($groupBy) $groupBySql = " GROUP BY " . $groupBy;
        if ($orderBy) $orderBySql = " ORDER BY " . $orderBy;

        if ($offset) {
            $limit = intval($limit);
            $offset = intval($offset);
            $limitSql = " LIMIT {$limit} , {$offset} ";
        }


        $sql = "SELECT *  FROM `{$tableName}` " . $whereSql . $groupBySql . $orderBySql . $limitSql;

        $key = base64_encode($sql).$returnCount;
        if(isset(self::$sqlinstance[$key]) && self::$sqlinstance[$key]) return self::$sqlinstance[$key];

        $rs = self::query($sql)->fetchAll();
        if ($returnCount) {
            $sqlCount = "SELECT count(*) as cnt FROM  `{$tableName}` " . $whereSql;
            $rsCount = self::query($sqlCount)->fetch();
            self::$_total = $rsCount['cnt'];
        }
        self::$sqlinstance[$key] = $rs;
        return $rs;
    }

    /**
     * 得到单个字段
     * @param  [type]  $field   [description]
     * @param  [type]  $where   [description]
     * @param  boolean $isMore [description]
     * @param  string $orderBy [description]
     * @param  string $limit [description]
     * @param  string $offset [description]
     * @param  string $groupBy [description]
     * @return [type]           [description]
     */
    static function getField($field, $where, $isMore = false,$resultKey, $orderBy = "", $limit = "", $offset = "", $groupBy = "")
    {
        $whereSql = self::parseWhere($where);
        $whereSql = $whereSql ? " WHERE " . $whereSql : "";
        $tableName = self::getTableName();
        $orderBySql = "";
        $groupBySql = "";
        $limitSql = "";
        if ($groupBy) $groupBySql = " GROUP BY " . $groupBy;
        if ($orderBy) $orderBySql = " ORDER BY " . $orderBy;

        if ($offset) {
            $limit = intval($limit);
            $offset = intval($offset);
            $limitSql = " LIMIT {$limit} , {$offset} ";
        }
        $resultKeyStr = $resultKey?$resultKey.",":"";
        $sql = "SELECT ".$resultKeyStr." " . $field . "  FROM `{$tableName}` " . $whereSql . $groupBySql . $orderBySql . $limitSql;
        $key = base64_encode($sql).$field.$isMore;
        if(isset(self::$sqlinstance[$key]) && self::$sqlinstance[$key]) return self::$sqlinstance[$key];
        if ($isMore) {
            $rs = self::query($sql)->fetchAll();
            if (!$rs) return array();
            $result = array();
            foreach ($rs as $key => $value) {
                $result[$value[$resultKey]] = $value[$field];
            }
            self::$sqlinstance[$key] = $result;
            return $result;
        } else {
            $rs = self::query($sql)->fetch();
            $result= $rs ? $rs[$field] : "";
            self::$sqlinstance[$key] = $result;
            return $result;
        }

    }


    /**
     * 根据条件获取一行
     * @param array $where
     */
    static  function get($where, $orderBy = "")
    {
        $whereSql = self::parseWhere($where);
        $whereSql = $whereSql ? " WHERE " . $whereSql : "";

        $tableName = self::getTableName();

        $sql = "SELECT * FROM `{$tableName}` " . $whereSql;
        if ($orderBy) $sql .= " ORDER BY " . $orderBy;

        $key = base64_encode($sql);
        if(isset(self::$sqlinstance[$key]) && self::$sqlinstance[$key]) return self::$sqlinstance[$key];
        $result = self::query($sql)->fetch();
        self::$sqlinstance[$key] = $result;
        return $result;
    }


    /**
     *
     * 获取数据行数
     * @param array $where
     */
    static  function getCount($where = array(), $groupBy = "")
    {
        $whereSql = self::parseWhere($where);
        $whereSql = $whereSql ? " WHERE " . $whereSql : "";
        $tableName = self::getTableName();
        $groupBySql = "";
        if ($groupBy) $groupBySql = " GROUP BY " . $groupBy;
        $sql = "SELECT count(*) as cnt FROM `{$tableName}` " . $whereSql . $groupBySql;
        $return = self::query($sql)->fetch();

        return $return['cnt'];
    }

    /**
     *
     * 获取sql
     */
    static  function getSql()
    {
        return self::$_sql;
    }

    /**
     *
     * 获取sql
     */
    static  function unsetSql()
    {
        self::$_sql=array();
    }

    static function backup($table){
        $db =self::getAdapter();
        $sql = "DROP TABLE IF EXISTS $table;\n";
        $createtable = $db->query("SHOW CREATE TABLE $table");
        $create = $createtable->fetch(PDO::FETCH_NUM);
        $sql .= $create[1].";\n\n";

        $rows = $db->query("SELECT * FROM $table");
        $numfields = $rows->columnCount ();

        while ($row = $rows->fetch(PDO::FETCH_NUM)){
            $comma = "";
            $sql .= "INSERT INTO $table VALUES(";
            for ($i = 0; $i < $numfields; $i++){
                $sql .= $comma."'".addslashes($row[$i])."'";
                $comma = ",";
            }
            $sql .= ");\n";
        }
        $sql .= "\n";
        return $sql;
    }

    static function import($sqlPath,$old_prefix="",$new_prefix=""){
        if(is_file($sqlPath)){
            $txt = file_get_contents($sqlPath);
            if(!$txt) return true;
            $sqlArr = self::clearSql($txt,$old_prefix,$new_prefix);
            if($sqlArr){
                foreach ($sqlArr as $sv){
                    self::getAdapter(0,0)->exec($sv);
                }
            }
        }
        return true;
    }

    /*
		参数：
		$old_prefix:原表前缀；
		$new_prefix:新表前缀；
		$separator:分隔符 参数可为";\n"或";\r\n"或";\r"
	*/
    static function clearSql($content,$old_prefix="",$new_prefix="",$separator=";\n")
    {
        $commenter = array('#', '--');
        $content = str_replace(array($old_prefix, "\r"), array($new_prefix, "\n"), $content);//替换前缀

        //通过sql语法的语句分割符进行分割
        $segment = explode($separator, trim($content));

        //去掉注释和多余的空行
        $data = array();
        foreach ($segment as $statement) {
            $sentence = explode("\n", $statement);
            $newStatement = array();
            foreach ($sentence as $subSentence) {
                if ('' != trim($subSentence)) {
                    //判断是会否是注释
                    $isComment = false;
                    foreach ($commenter as $comer) {
                        if (preg_match("/^(" . $comer . ")/is", trim($subSentence))) {
                            $isComment = true;
                            break;
                        }
                    }
                    //如果不是注释，则认为是sql语句
                    if (!$isComment)
                        $newStatement[] = $subSentence;
                }
            }
            $data[] = $newStatement;
        }

        //组合sql语句
        foreach ($data as $statement) {
            $newStmt = '';
            foreach ($statement as $sentence) {
                $newStmt = $newStmt . trim($sentence) . "\n";
            }
            if (!empty($newStmt)) {
                $result[] = $newStmt;
            }
        }
        return $result;
    }

    function __destruct(){
        $sql = self::getSql();
        if($sql){
            foreach($sql as $v){
                tr_log::debug($v);
            }
        }
        self::$_sql=null;
    }


}
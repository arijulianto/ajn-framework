<?php

class Database{
    private $host      = 'localhost';
    private $username  = 'root';
    private $password  = '';
    private $dbname    = '';
    private $port      = '3306';
    private $utf       = 'utf8';

    public $isConnected = false;

    private $dbh;
    private $pdo;

    public $aResults = array();
    public $iAffectedRows = 0;
    public $iLastId = 0;
    public $iAllLastId = array();

    private $aValidOperation = array('SELECT', 'INSERT', 'UPDATE', 'DELETE');
    private $sQuery = '';

    public function __construct($config){
        if($config){
            if(is_array($config)){
                if($config['host']) $this->host = $config['host'];
                if($config['username']) $this->username = $config['username'];
                if($config['password']) $this->password = $config['password'];
                if($config['dbname']) $this->dbname = $config['dbname'];
                if($config['utf']) $this->utf = $config['utf'];
            }else{
                $this->dbname = $config;
            }
        }
        $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname.';port='.$this->port;
      // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$this->utf,
            PDO::ATTR_EMULATE_PREPARES => true,
        );

        try {
            $this->dbh = new PDO($dsn, $this->username, $this->password, $options);
            $this->isConnected = true;
        } catch (PDOException $e) {
            $this->error($e->getMessage());
        }
    }

    public function query($sQuery, $bindParamWhere=[])
    {
        $sQuery       = trim($sQuery);
        $operation    = explode(' ', $sQuery);
        $operation[0] = strtoupper($operation[0]);

        $this->sQuery = $sQuery;
        if(!in_array($operation[0], $this->aValidOperation)){
            $this->error('Invalid operation for `'.$operation[0].'`. use only '.implode(', ',$this->aValidOperation));
        }else{
            $this->pdo = $this->dbh->prepare($sQuery);
            $this->_bindParam($bindParamWhere);
            try{
                if($this->pdo->execute()){
                    switch($operation[0]){
                            case 'SELECT':
                                $this->iAffectedRows = $this->pdo->rowCount();
                                $this->aResults      = $this->pdo->fetchAll(PDO::FETCH_ASSOC);
                                return $this;
                                break;
                            case 'INSERT':
                                $this->iLastId = $this->lastInsertId();
                                return $this;
                                break;
                            case 'UPDATE':
                                $this->iAffectedRows = $this->pdo->rowCount();
                                return $this;
                                break;
                            case 'DELETE':
                                $this->iAffectedRows = $this->pdo->rowCount();
                                return $this;
                                break;
                        }
                        $this->pdo->closeCursor();
                }else{
                    $this->error($this->pdo->errorInfo());
                }
            }
            catch(PDOException $e){
                $this->error($e->getMessage());
            }
        }
    }

    public function insert($sTable, $aData = array()){
        foreach($aData as $f => $v){
                $tmp[] = ":s_$f";
            }
            $sNameSpaceParam = implode(',', $tmp);
            unset($tmp);
            $sFields     = implode(',', array_keys($aData));
            $this->sQuery  = "INSERT INTO `$sTable`($sFields) VALUES($sNameSpaceParam);";
            $this->pdo = $this->dbh->prepare($this->sQuery);
            $this->aData = $aData;
            $this->_bindNamespace($aData);
            try{
                if($this->pdo->execute()){
                    $this->iLastId = $this->dbh->lastInsertId();
                    $this->pdo->closeCursor();
                    return $this;
                } else{
                    $this->error($this->pdo->errorInfo());
                }
            }
            catch(PDOException $e){
                $this->error($e->getMessage());
            }
    }

    public function insertBatch($sTable, $aData = array()){
        if($aData[0]){
            $this->beginTrans();
            foreach($aData[0] as $f => $v){
                    $tmp[] = ":s_$f";
                }
                $sNameSpaceParam = implode(', ', $tmp);
                unset($tmp);
                $sFields = implode(', ', array_keys($aData[0]));
                $this->sQuery  = "INSERT INTO `$sTable`($sFields) VALUES($sNameSpaceParam);";
                $this->pdo = $this->dbh->prepare($this->sQuery);
                foreach($aData as $key => $value){
                    $this->_bindNamespace($value);
                    try{
                        if($this->pdo->execute()){
                            $this->iAllLastId[] = $this->dbh->lastInsertId();
                        } else{
                            $this->error($this->pdo->errorInfo());
                            $this->rollBack();
                        }
                    }
                    catch(PDOException $e){
                        $this->error($e->getMessage());
                        $this->rollBack();
                    }
                }
                $this->endTrans();
                $this->pdo->closeCursor();
                return $this;
        }
    }

    public function update($sTable, $aData = array(), $aWhere = array()){
        foreach($aData as $k => $v){
            $tmp[] = "$k = :s_$k";
        }
        $sFields = implode(', ', $tmp);
        unset($tmp);
        foreach($aWhere as $k => $v){
            $tmp[] = "$k = :s_$k";
        }
        $sWhere = implode(' AND ', $tmp);
        unset($tmp);
        $this->sQuery  = "UPDATE `$sTable` SET $sFields WHERE $sWhere";
        $this->pdo = $this->dbh->prepare($this->sQuery);
        $this->_bindNamespace($aData);
        $this->_bindNamespace($aWhere);
        try{
            if($this->pdo->execute()){
                $this->iAffectedRows = $this->pdo->rowCount();
                $this->pdo->closeCursor();
                return $this;
            } else{
                $this->error($this->pdo->errorInfo());
            }
        }
        catch(PDOException $e){
            $this->error($e->getMessage());
        }
    }

    public function delete($sTable, $aWhere = array()){
        foreach($aWhere as $k => $v){
            $tmp[] = "$k = :s_$k";
        }
        $sWhere = implode(' AND ', $tmp);
        unset($tmp);
        $this->sQuery  = "DELETE FROM `$sTable` WHERE $sWhere";
        $this->pdo = $this->dbh->prepare($this->sQuery);
        $this->_bindNamespace($aWhere);
        try{
            if($this->pdo->execute()){
                $this->iAffectedRows = $this->pdo->rowCount();
                $this->pdo->closeCursor();
                return $this;
            } else{
                $this->error($this->pdo->errorInfo());
            }
        }
        catch(PDOException $e){
            $this->error($e->getMessage());
        }
    }

    public function count($sTable, $sWhere = ''){
        if(empty($sWhere)){
            $this->sQuery  = "SELECT COUNT(*) AS NUMROWS FROM `$sTable`;";
        }else{
            $this->sQuery  = "SELECT COUNT(*) AS NUMROWS FROM `$sTable` WHERE $sWhere;";
        }
        $this->pdo = $this->dbh->prepare($this->sQuery);
        try{
            if($this->pdo->execute()){
                $this->aResults = $this->pdo->fetch();
                $this->pdo->closeCursor();
                return $this->aResults['NUMROWS'];
            } else{
                $this->error($this->pdo->errorInfo());
            }
        }
        catch(PDOException $e){
            $this->error($e->getMessage());
        }
    }

    public function truncate($sTable){
        $this->sQuery  = "TRUNCATE TABLE `$sTable`;";
        $this->pdo = $this->dbh->prepare($this->sQuery);
        try{
            if($this->pdo->execute()){
                $this->pdo->closeCursor();
                return true;
            } else{
                $this->error($this->pdo->errorInfo());
            }
        }
        catch(PDOException $e){
            $this->error($e->getMessage());
        }
    }

    public function dropTable($sTable){
        $this->sQuery  = "DROP TABLE `$sTable`;";
        $this->pdo = $this->dbh->prepare($this->sQuery);
        try{
            if($this->pdo->execute()){
                $this->pdo->closeCursor();
                return true;
            } else{
                $this->error($this->pdo->errorInfo());
            }
        }
        catch(PDOException $e){
            $this->error($e->getMessage());
        }
    }

    public function dropView($sView){
        $this->sQuery  = "DROP VIEW `$sView`;";
        $this->pdo = $this->dbh->prepare($this->sQuery);
        try{
            if($this->pdo->execute()){
                $this->pdo->closeCursor();
                return true;
            } else{
                $this->error($this->pdo->errorInfo());
            }
        }
        catch(PDOException $e){
            $this->error($e->getMessage());
        }
    }

    public function createTable($sTable, $fields, $pk){
        $tmp = [];
        foreach($fields as $name=>$attr){
            //$tmp[] = "$name $attr".($name==$pk?(strpos($attr,'int')?' AUTO_INCREMENT PRIMARY KEY ':'').' PRIMARY KEY':'');
            if(strtolower($name)==strtolower($pk)){
                $tmp[] = "$name $attr AUTO_INCREMENT PRIMARY KEY NOT NULL";
            }else{
                $tmp[] = "$name $attr";
            }
        }
        $this->sQuery = "CREATE TABLE IF NOT EXISTS `$sTable` (".implode(",", $tmp).")";
        $this->pdo = $this->dbh->prepare($this->sQuery);
        try{
            if($this->pdo->execute()){
                $this->pdo->closeCursor();
                return true;
            } else{
                $this->error($this->pdo->errorInfo());
            }
        }
        catch(PDOException $e){
          $this->error($e->getMessage());
        }
    }

    public function describe($sTable){
        $this->sQuery = $sQuery  = "DESC $sTable;";
        $this->pdo = $this->dbh->prepare($sQuery);
        $this->pdo->execute();
        $aColList = $this->pdo->fetchAll();
        foreach($aColList as $key){
            $aField[] = $key['Field'];
            $aType[]  = $key['Type'];
        }
        return array_combine($aField, $aType);
    }

    public function execSP($spName, $params = []){
        $tmp_a = [];
        $tmp_b = [];
        $param = '';
        if($params){
            foreach($params as $p){
                $tmp_a[] = '?';
                $tmp_b[] = $p;
            }
            $param = '('.implode(',', $tmp_a).')';
        }
        $this->sQuery = "CALL $spName$param";
        $this->pdo = $this->dbh->prepare($this->sQuery);

        if($param){
            $this->_bindParam($tmp_b);
        }
 
        try{
            if($this->pdo->execute()){
                $this->iAffectedRows = $this->pdo->rowCount();
                $this->aResults = $this->pdo->fetchAll(PDO::FETCH_ASSOC);
                return $this;
            }
        }
        catch(PDOException $e){
            $this->error($e->getMessage());
        }
    }

    private function _bindNamespace($array = array()){
        if(count($array)>0){
            foreach($array as $f => $v){
                switch(gettype($array[$f])){
                    case 'string':
                        $this->pdo->bindParam(":s_$f", $array[$f], PDO::PARAM_STR);
                        break;
                    case 'integer':
                        $this->pdo->bindParam(":s_$f", $array[$f], PDO::PARAM_INT);
                        break;
                    case 'boolean':
                        $this->pdo->bindParam(":s_$f", $array[$f], PDO::PARAM_BOOL);
                        break;
                    default:
                        $this->pdo->bindParam(":s_$f", $array[$f], PDO::PARAM_STR);
                }
            }
        }
    }

    private function _bindParam($array = array()){
        if(count($array)>0){
            foreach($array as $f => $v){
                switch(gettype($array[$f])){
                    case 'string':
                        $this->pdo->bindParam($f + 1, $array[$f], PDO::PARAM_STR);
                        break;
                    case 'integer':
                        $this->pdo->bindParam($f + 1, $array[$f], PDO::PARAM_INT);
                        break;
                    case 'boolean':
                        $this->pdo->bindParam($f + 1, $array[$f], PDO::PARAM_BOOL);
                        break;
                    default:
                        $this->pdo->bindParam($f + 1, $array[$f], PDO::PARAM_STR);
                }
            }
        }
    }

    public function result(){
        return isset($this->aResults[0]) ? $this->aResults[0] : false;
    }

    public function results(){
        return isset($this->aResults) ? $this->aResults : false;
    }

    public function affectedRows(){
        return is_numeric($this->iAffectedRows) ? $this->iAffectedRows : false;
    }

    public function getLastInsertId(){
        return $this->iLastId;
    }

    public function getAllLastInsertId(){
        return $this->iAllLastId;
    }

    public function beginTrans(){
        return $this->dbh->beginTransaction();
    }

    public function endTrans(){
        return $this->dbh->commit();
    }

    public function rollBack(){
        return $this->dbh->rollBack();
    }

    public function error($data = array()){
        die('<div style="color:#333846; border:1px solid #777; padding:2px; background-color: #FFC0CB;margin-bottom:3px">ERROR: '.json_encode($data)."</div>");
    }

    public function showQuery(){
        echo "<div style='color:#990099; border:1px solid #777; padding:2px; background-color: #E5E5E5;margin-bottom:3px'>";
        echo " Executed Query -> <span style='color:#008000;'> ";
        echo $this->formatSQL($this->sQuery);
        echo "</span></div>";
        return $this;
    }

    public function formatSQL($sql){
        $sqlKey = "select|insert|update|delete|truncate|drop|create|add|except|percent|all|exec|plan|alter|execute|precision|and|exists|primary|any|exit|print|as|fetch|proc|asc|file|procedure|authorization|fillfactor|public|backup|for|raiserror|begin|foreign|read|between|freetext|readtext|break|freetexttable|reconfigure|browse|from|references|bulk|full|replication|by|function|restore|cascade|goto|restrict|case|grant|return|check|group|revoke|checkpoint|having|right|close|holdlock|rollback|clustered|identity|rowcount|coalesce|identity_insert|rowguidcol|collate|identitycol|rule|column|if|save|commit|in|schema|compute|index|select|constraint|inner|session_user|contains|insert|set|containstable|intersect|setuser|continue|into|shutdown|convert|is|some|create|join|statistics|cross|key|system_user|current|kill|table|current_date|left|textsize|current_time|like|then|current_timestamp|lineno|to|current_user|load|top|cursor|national|tran|database|nocheck|transaction|dbcc|nonclustered|trigger|deallocate|not|truncate|declare|null|tsequal|default|nullif|union|delete|of|unique|deny|off|update|desc|offsets|updatetext|disk|on|use|distinct|open|user|distributed|opendatasource|values|double|openquery|varying|drop|openrowset|view|dummy|openxml|waitfor|dump|option|when|else|or|where|end|order|while|errlvl|outer|with|escape|over|writetext|absolute|overlaps|action|pad|ada|partial|external|pascal|extract|position|allocate|false|prepare|first|preserve|float|are|prior|privileges|fortran|assertion|found|at|real|avg|get|global|relative|go|bit|bit_length|both|rows|hour|cascaded|scroll|immediate|second|cast|section|catalog|include|char|session|char_length|indicator|character|initially|character_length|size|input|smallint|insensitive|space|int|sql|collation|integer|sqlca|sqlcode|interval|sqlerror|connect|sqlstate|connection|sqlwarning|isolation|substring|constraints|sum|language|corresponding|last|temporary|count|leading|time|level|timestamp|timezone_hour|local|timezone_minute|lower|match|trailing|max|min|translate|date|minute|translation|day|module|trim|month|true|dec|names|decimal|natural|unknown|nchar|deferrable|next|upper|deferred|no|usage|none|using|describe|value|descriptor|diagnostics|numeric|varchar|disconnect|octet_length|domain|only|whenever|work|end-exec|write|year|output|zone|exception|free|admin|general|after|reads|aggregate|alias|recursive|grouping|ref|host|referencing|array|ignore|result|returns|before|role|binary|initialize|rollup|routine|blob|inout|row|boolean|savepoint|breadth|call|scope|search|iterate|large|sequence|class|lateral|sets|clob|less|completion|limit|specific|specifictype|localtime|constructor|localtimestamp|sqlexception|locator|cube|map|current_path|start|current_role|state|cycle|modifies|statement|data|modify|static|structure|terminate|than|nclob|depth|new|deref|destroy|treat|destructor|object|deterministic|old|under|dictionary|operation|unnest|ordinality|out|dynamic|each|parameter|variable|equals|parameters|every|without|path|postfix|prefix|preorder";
        $list = explode('|',$sqlKey);
        foreach($list as &$verb){
            $verb = '/\b' . preg_quote($verb, '/') . '\b/';
        }
        $regex_sign = array('/\b','\b/');
        return str_replace($regex_sign,'',preg_replace($list, array_map(array(
            $this,
            'highlight_sql'
       ), $list), strtolower($sql)));
    }
   
    public function highlight_sql($param){
        return '<span style="color:#990099;font-weight:bold;text-transform:uppercase;">'.$param.'</span>';
    }
}
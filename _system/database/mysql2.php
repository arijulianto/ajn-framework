<?php
class PDOHelper{
    public function arrayToXml($arrayData = array()){
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xml .= "<root>";
        foreach($arrayData as $key => $value){
            $xml .= "<xml_data>";
            if(is_array($value)){
                foreach($value as $k => $v){
                    $xml .= "<$k>";
                    $xml .= "<![CDATA[$v]]>";
                    $xml .= "</$k>";
                }
            } else{
                $xml .= "<$key>";
                $xml .= "<![CDATA[$value]]>";
                $xml .= "</$key>";
            }
            $xml .= "</xml_data>";
        }
        $xml .= "</root>";
        return $xml;
    }

    public function formatSQL($sql = ''){
        $reserveSqlKey = "select|insert|update|delete|truncate|drop|create|add|except|percent|all|exec|plan|alter|execute|precision|and|exists|primary|any|exit|print|as|fetch|proc|asc|file|procedure|authorization|fillfactor|public|backup|for|raiserror|begin|foreign|read|between|freetext|readtext|break|freetexttable|reconfigure|browse|from|references|bulk|full|replication|by|function|restore|cascade|goto|restrict|case|grant|return|check|group|revoke|checkpoint|having|right|close|holdlock|rollback|clustered|identity|rowcount|coalesce|identity_insert|rowguidcol|collate|identitycol|rule|column|if|save|commit|in|schema|compute|index|select|constraint|inner|session_user|contains|insert|set|containstable|intersect|setuser|continue|into|shutdown|convert|is|some|create|join|statistics|cross|key|system_user|current|kill|table|current_date|left|textsize|current_time|like|then|current_timestamp|lineno|to|current_user|load|top|cursor|national|tran|database|nocheck|transaction|dbcc|nonclustered|trigger|deallocate|not|truncate|declare|null|tsequal|default|nullif|union|delete|of|unique|deny|off|update|desc|offsets|updatetext|disk|on|use|distinct|open|user|distributed|opendatasource|values|double|openquery|varying|drop|openrowset|view|dummy|openxml|waitfor|dump|option|when|else|or|where|end|order|while|errlvl|outer|with|escape|over|writetext|absolute|overlaps|action|pad|ada|partial|external|pascal|extract|position|allocate|false|prepare|first|preserve|float|are|prior|privileges|fortran|assertion|found|at|real|avg|get|global|relative|go|bit|bit_length|both|rows|hour|cascaded|scroll|immediate|second|cast|section|catalog|include|char|session|char_length|indicator|character|initially|character_length|size|input|smallint|insensitive|space|int|sql|collation|integer|sqlca|sqlcode|interval|sqlerror|connect|sqlstate|connection|sqlwarning|isolation|substring|constraints|sum|language|corresponding|last|temporary|count|leading|time|level|timestamp|timezone_hour|local|timezone_minute|lower|match|trailing|max|min|translate|date|minute|translation|day|module|trim|month|true|dec|names|decimal|natural|unknown|nchar|deferrable|next|upper|deferred|no|usage|none|using|describe|value|descriptor|diagnostics|numeric|varchar|disconnect|octet_length|domain|only|whenever|work|end-exec|write|year|output|zone|exception|free|admin|general|after|reads|aggregate|alias|recursive|grouping|ref|host|referencing|array|ignore|result|returns|before|role|binary|initialize|rollup|routine|blob|inout|row|boolean|savepoint|breadth|call|scope|search|iterate|large|sequence|class|lateral|sets|clob|less|completion|limit|specific|specifictype|localtime|constructor|localtimestamp|sqlexception|locator|cube|map|current_path|start|current_role|state|cycle|modifies|statement|data|modify|static|structure|terminate|than|nclob|depth|new|deref|destroy|treat|destructor|object|deterministic|old|under|dictionary|operation|unnest|ordinality|out|dynamic|each|parameter|variable|equals|parameters|every|without|path|postfix|prefix|preorder";
        $list = explode('|',$reserveSqlKey);
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
        return "<span style='color:#990099; font-weight:bold; text-transform: uppercase;'>$param</span>";
    }

    public function displayHtmlTable($aColList = array()){
        $r        = '';
        if(count($aColList) > 0){
            $r .= '<table border="1">';
            $r .= '<thead>';
            $r .= '<tr>';
            foreach($aColList[0] as $k => $v){
                $r .= '<td>' . $k . '</td>';
            }
            $r .= '</tr>';
            $r .= '</thead>';
            $r .= '<tbody>';
            foreach($aColList as $record){
                $r .= '<tr>';
                foreach($record as $data){
                    $r .= '<td>' . $data . '</td>';
                }
                $r .= '</tr>';
            }
            $r .= '</tbody>';
            $r .= '<table>';
        } else{
            $r .= '<div class="no-results">No results found for query.</div>';
        }
        return $r;
    }

    public function isAssocArray($array = array()){
        return array_keys($array) !== range(0, count($array) - 1);
    }

    public function PA($array){
        echo '<pre>', print_r($array, true), '</pre>';
    }

    public function error(){
        $style = "style='color:#333846; border:1px solid #777; padding:2px; background-color: #FFC0CB;'";
        die("<div $style >ERROR: error occurred. Please, Check you error log file.</div>");
    }

    public function errorBox($data = array()){
        $style = "style='color:#333846; border:1px solid #777; padding:2px; background-color: #FFC0CB;'";
        die("<div $style >ERROR:" . json_encode($data) . "</div>");
    }

}



class Database extends PDO{
    private $_oSTH = null;
    public $sSql = '';
    public $sTable = array();
    public $aWhere = array();
    public $aColumn = array();
    public $sOther = array();
    public $aResults = array();
    public $aResult = array();
    public $iLastId = 0;
    public $iAllLastId = array();
    public $sPdoError = '';
    public $iAffectedRows = 0;
    public $aData = null;
    public $log = false;
    public $batch = false;
    const ERROR_LOG_FILE = 'PDO_Errors.log';
    const SQL_LOG_FILE = 'PDO_Sql.log';
    private $db_info = array('host'=>'localhost', 'username'=>'root', 'password'=>'');
    private $aValidOperation = array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'EXEC');
    protected static $oPDO = null;

    public function __construct($db_info=array()){
        if(!$db_info){
            die("No database selected!");
        }
        if(is_array($db_info)){
            $this->db_info['dbname'] = $db_info['dbname'];
            if($db_info['host']) $this->db_info['host'] = $db_info['host'];
            if($db_info['username']) $this->db_info['username'] = $db_info['username'];
            if($db_info['password']) $this->db_info['password'] = $db_info['password'];
        }else{
            $this->db_info['dbname'] = $db_info;
        }
        try{
            parent::__construct(
                'mysql:host='.$this->db_info['host'].'; dbname='.$this->db_info['dbname'],
                $this->db_info['username'],
                $this->db_info['password'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
           );
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            die("ERROR in establish connection: ".$e->getMessage());
        }

    }

    public function __destruct(){
        self::$oPDO = null;
    }

    public static function getPDO($dsn = array()){
        if(!isset(self::$oPDO) ||(self::$oPDO !== null)){
            self::$oPDO = new self($dsn);
        }
        return self::$oPDO;
    }

    public function start(){
        $this->beginTransaction();
    }

    public function end(){
        $this->commit();
    }

    public function back(){
        $this->rollback();
    }

    public function result($iRow = 0){
        return isset($this->aResults[$iRow]) ? $this->aResults[$iRow] : false;
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

    public function helper(){
        return new PDOHelper();
    }

    public function query($sSql = '', $aBindWhereParam = array()){
        $sSql         = trim($sSql);
        $operation    = explode(' ', $sSql);
        $operation[0] = strtoupper($operation[0]);
        if(!in_array($operation[0], $this->aValidOperation)){
            self::error('invalid operation called in query. use only ' . implode(', ', $this->aValidOperation));
        }
        if(!empty($sSql) && count($aBindWhereParam) <= 0){
            $this->sSql  = $sSql;
            $this->_oSTH = $this->prepare($this->sSql);
            try{
                if($this->_oSTH->execute()){
                    $this->iAffectedRows = $this->_oSTH->rowCount();
                    $this->aResults      = $this->_oSTH->fetchAll();
                    $this->_oSTH->closeCursor();
                    return $this;
                } else{
                    self::error($this->_oSTH->errorInfo());
                }
            }
            catch(PDOException $e){
                self::error($e->getMessage() . ': ' . __LINE__);
            }
        }
        else if(!empty($sSql) && count($aBindWhereParam) > 0){
            $this->sSql   = $sSql;
            $this->aData = $aBindWhereParam;
            $this->_oSTH  = $this->prepare($this->sSql);
            $this->_bindPdoParam($aBindWhereParam);
            try{
                if($this->_oSTH->execute()){
                    switch($operation[0]):
                        case 'SELECT':
                            $this->iAffectedRows = $this->_oSTH->rowCount();
                            $this->aResults      = $this->_oSTH->fetchAll();
                            return $this;
                            break;
                        case 'INSERT':
                            $this->iLastId = $this->lastInsertId();
                            return $this;
                            break;
                        case 'UPDATE':
                            $this->iAffectedRows = $this->_oSTH->rowCount();
                            return $this;
                            break;
                        case 'DELETE':
                            $this->iAffectedRows = $this->_oSTH->rowCount();
                            return $this;
                            break;
                    endswitch;
                    $this->_oSTH->closeCursor();
                } else{
                    self::error($this->_oSTH->errorInfo());
                }
            }
            catch(PDOException $e){
                self::error($e->getMessage() . ': ' . __LINE__);
            }
        } else{
            self::error('Query is empty..');
        }
    }

    public function select($sTable = '', $aColumn = array(), $aWhere = array(), $sOther = ''){
        if(!is_array($aColumn))$aColumn = array();
        $sField = count($aColumn) > 0 ? implode(', ', $aColumn) : '*';
        if(!empty($sTable)){
            if(count($aWhere) > 0 && is_array($aWhere)){
                $this->aData = $aWhere;
                if(strstr(key($aWhere), ' ')){
                    $tmp = $this->customWhere($this->aData);
                    $sWhere = $tmp['where'];
                }else{
                    foreach($aWhere as $k => $v){
                        $tmp[] = "$k = :s_$k";
                    }
                    $sWhere = implode(' AND ', $tmp);
                }
                unset($tmp);
                $this->sSql = "SELECT $sField FROM `$sTable` WHERE $sWhere $sOther;";
            } else{
                $this->sSql = "SELECT $sField FROM `$sTable` $sOther;";
            }
            $this->_oSTH = $this->prepare($this->sSql);
            if(count($aWhere) > 0 && is_array($aWhere)){
               $this->_bindPdoNameSpace($aWhere);
            }
            try{
                if($this->_oSTH->execute()){
                    $this->iAffectedRows = $this->_oSTH->rowCount();
                    $this->aResults      = $this->_oSTH->fetchAll();
                    $this->_oSTH->closeCursor();
                    return $this;
                } else{
                    self::error($this->_oSTH->errorInfo());
                }
            }
            catch(PDOException $e){
                self::error($e->getMessage() . ': ' . __LINE__);
            }
        } 
        else{
            self::error('Table name not found..');
        }
    }

    public function insert($sTable, $aData = array()){
        if(!empty($sTable)){
            if(count($aData) > 0 && is_array($aData)){
                foreach($aData as $f => $v){
                    $tmp[] = ":s_$f";
                }
                $sNameSpaceParam = implode(',', $tmp);
                unset($tmp);
                $sFields     = implode(',', array_keys($aData));
                $this->sSql  = "INSERT INTO `$sTable`($sFields) VALUES($sNameSpaceParam);";
                $this->_oSTH = $this->prepare($this->sSql);
                $this->aData = $aData;
                $this->_bindPdoNameSpace($aData);
                try{
                    if($this->_oSTH->execute()){
                        $this->iLastId = $this->lastInsertId();
                        $this->_oSTH->closeCursor();
                        return $this;
                    } else{
                        self::error($this->_oSTH->errorInfo());
                    }
                }
                catch(PDOException $e){
                    self::error($e->getMessage() . ': ' . __LINE__);
                }
            } else{
                self::error('Data not in valid format..');
            }
        } else{
            self::error('Table name not found..');
        }
    }

    public function insertBatch($sTable, $aData = array(), $safeModeInsert = true){
        $this->start();
        if(!empty($sTable)){
            if(count($aData) > 0 && is_array($aData)){
                foreach($aData[0] as $f => $v){
                    $tmp[] = ":s_$f";
                }
                $sNameSpaceParam = implode(', ', $tmp);
                unset($tmp);
                $sFields = implode(', ', array_keys($aData[0]));
                if(!$safeModeInsert){
                    $this->sSql = "INSERT INTO `$sTable`($sFields) VALUES ";
                    foreach($aData as $key => $value){
                        $this->sSql .= '(' . "'" . implode("', '", array_values($value)) . "'" . '), ';
                    }
                    $this->sSql  = rtrim($this->sSql, ', ');
                    $this->_oSTH = $this->prepare($this->sSql);
                    try{
                        if($this->_oSTH->execute()){
                            $this->iAllLastId[] = $this->lastInsertId();
                        } else{
                            self::error($this->_oSTH->errorInfo());
                        }
                    }
                    catch(PDOException $e){
                        self::error($e->getMessage() . ': ' . __LINE__);
                        $this->back();
                    }
                    $this->end();
                    $this->_oSTH->closeCursor();
                    return $this;
                }
                $this->sSql  = "INSERT INTO `$sTable`($sFields) VALUES($sNameSpaceParam);";
                $this->_oSTH = $this->prepare($this->sSql);
                $this->aData = $aData;
                $this->batch = true;
                foreach($aData as $key => $value){
                    $this->_bindPdoNameSpace($value);
                    try{
                        if($this->_oSTH->execute()){
                            $this->iAllLastId[] = $this->lastInsertId();
                        } else{
                            self::error($this->_oSTH->errorInfo());
                            $this->back();
                        }
                    }
                    catch(PDOException $e){
                        self::error($e->getMessage() . ': ' . __LINE__);
                        $this->back();
                    }
                }
                $this->end();
                $this->_oSTH->closeCursor();
                return $this;
            } else{
                self::error('Data not in valid format..');
            }
        } else{
            self::error('Table name not found..');
        }
    }

    public function update($sTable = '', $aData = array(), $aWhere = array(), $sOther = ''){
        if(!empty($sTable)){
            if(count($aData) > 0 && count($aWhere) > 0){
                foreach($aData as $k => $v){
                    $tmp[] = "$k = :s_$k";
                }
                $sFields = implode(', ', $tmp);
                unset($tmp);
                foreach($aWhere as $k => $v){
                    $tmp[] = "$k = :s_$k";
                }
                $this->aData = $aData;
                $this->aWhere = $aWhere;
                $sWhere = implode(' AND ', $tmp);
                unset($tmp);
                $this->sSql  = "UPDATE `$sTable` SET $sFields WHERE $sWhere $sOther;";
                $this->_oSTH = $this->prepare($this->sSql);
                $this->_bindPdoNameSpace($aData);
                $this->_bindPdoNameSpace($aWhere);
                try{
                    if($this->_oSTH->execute()){
                        $this->iAffectedRows = $this->_oSTH->rowCount();
                        $this->_oSTH->closeCursor();
                        return $this;
                    } else{
                        self::error($this->_oSTH->errorInfo());
                    }
                }
                catch(PDOException $e){
                    self::error($e->getMessage() . ': ' . __LINE__);
                }
            } else{
                self::error('update statement not in valid format..');
            }
        } else{
            self::error('Table name not found..');
        }
    }

    public function delete($sTable, $aWhere = array(), $sOther = ''){
        if(!empty($sTable)){
            if(count($aWhere) > 0 && is_array($aWhere)){
                foreach($aWhere as $k => $v){
                    $tmp[] = "$k = :s_$k";
                }
                $sWhere = implode(' AND ', $tmp);
                unset($tmp);
                $this->sSql  = "DELETE FROM `$sTable` WHERE $sWhere $sOther;";
                $this->_oSTH = $this->prepare($this->sSql);
                $this->_bindPdoNameSpace($aWhere);
                $this->aData = $aWhere;
                try{
                    if($this->_oSTH->execute()){
                        $this->iAffectedRows = $this->_oSTH->rowCount();
                        $this->_oSTH->closeCursor();
                        return $this;
                    } else{
                        self::error($this->_oSTH->errorInfo());
                    }
                }
                catch(PDOException $e){
                    self::error($e->getMessage() . ': ' . __LINE__);
                }
            } else{
                self::error('Not a valid where condition..');
            }
        } else{
            self::error('Table name not found..');
        }
    }

    public function results($type = 'array'){
        switch($type){
            case 'array':
                return $this->aResults;
                break;
            case 'xml':
                header("Content-Type:text/xml");
                return $this->helper()->arrayToXml($this->aResults);
                break;
            case 'json':
                header('Content-type: application/json; charset="utf-8"');
                return json_encode($this->aResults);
                break;
        }
    }

    public function count($sTable = '', $sWhere = ''){
        if(!empty($sTable)){
            if(empty($sWhere)){
                $this->sSql  = "SELECT COUNT(*) AS NUMROWS FROM `$sTable`;";
            }else{
                $this->sSql  = "SELECT COUNT(*) AS NUMROWS FROM `$sTable` WHERE $sWhere;";
            }
            $this->_oSTH = $this->prepare($this->sSql);
            try{
                if($this->_oSTH->execute()){
                    $this->aResults = $this->_oSTH->fetch();
                    $this->_oSTH->closeCursor();
                    return $this->aResults['NUMROWS'];
                } else{
                    self::error($this->_oSTH->errorInfo());
                }
            }
            catch(PDOException $e){
                self::error($e->getMessage() . ': ' . __LINE__);
            }
        } else{
            self::error('Table name not found..');
        }
    }

    public function truncate($sTable =''){
        if(!empty($sTable)){
            $this->sSql  = "TRUNCATE TABLE `$sTable`;";
            $this->_oSTH = $this->prepare($this->sSql);
            try{
                if($this->_oSTH->execute()){
                    $this->_oSTH->closeCursor();
                    return true;
                } else{
                    self::error($this->_oSTH->errorInfo());
                }
            }
            catch(PDOException $e){
                self::error($e->getMessage() . ': ' . __LINE__);
            }
        } else{
            self::error('Table name not found..');
        }
    }

    public function drop($sTable =''){
        if(!empty($sTable)){
            $this->sSql  = "DROP TABLE `$sTable`;";
            $this->_oSTH = $this->prepare($this->sSql);
            try{
                if($this->_oSTH->execute()){
                    $this->_oSTH->closeCursor();
                    return true;
                } else{
                    self::error($this->_oSTH->errorInfo());
                }
            }
            catch(PDOException $e){
                self::error($e->getMessage() . ': ' . __LINE__);
            }
        } else{
            self::error('Table name not found..');
        }
    }

    public function describe($sTable = ''){
        $this->sSql = $sSql  = "DESC $sTable;";
        $this->_oSTH = $this->prepare($sSql);
        $this->_oSTH->execute();
        $aColList = $this->_oSTH->fetchAll();
        foreach($aColList as $key){
            $aField[] = $key['Field'];
            $aType[]  = $key['Type'];
        }
        return array_combine($aField, $aType);
    }

    public function customWhere($array_data = array()){
        $syntax = '';
        foreach($array_data as $key => $value){
            $key = trim($key);
            if(strstr($key, ' ')){
                $array = explode(' ',$key);
                if(count($array)=='2'){
                    $random = '';
                    $field = $array[0];
                    $operator  = $array[1];
                    $tmp[] = "$field $operator :s_$field"."$random";
                    $syntax .= " $field $operator :s_$field"."$random ";
                }elseif(count($array)=='3'){
                    $random = '';
                    $condition = $array[0];
                    $field = $array[1];
                    $operator = $array[2];
                    $tmp[] = "$condition $field $operator :s_$field"."$random";
                    $syntax .= " $condition $field $operator :s_$field"."$random ";
                }
            }
        }
        return array(
            'where' => $syntax,
            'bind' => implode(' ',$tmp)
       );
    }

    private function _bindPdoNameSpace($array = array()){
        if(strstr(key($array), ' ')){
            foreach($array as $f => $v){
                $field = $this->getFieldFromArrayKey($f);
                switch(gettype($array[$f])):
                    case 'string':
                        $this->_oSTH->bindParam(":s" . "_" . "$field", $array[$f], PDO::PARAM_STR);
                        break;
                    case 'integer':
                        $this->_oSTH->bindParam(":s" . "_" . "$field", $array[$f], PDO::PARAM_INT);
                        break;
                    case 'boolean':
                        $this->_oSTH->bindParam(":s" . "_" . "$field", $array[$f], PDO::PARAM_BOOL);
                        break;
                endswitch;
            } 
        }else{
        foreach($array as $f => $v){
            switch(gettype($array[$f])):
                case 'string':
                    $this->_oSTH->bindParam(":s" . "_" . "$f", $array[$f], PDO::PARAM_STR);
                    break;
                case 'integer':
                    $this->_oSTH->bindParam(":s" . "_" . "$f", $array[$f], PDO::PARAM_INT);
                    break;
                case 'boolean':
                    $this->_oSTH->bindParam(":s" . "_" . "$f", $array[$f], PDO::PARAM_BOOL);
                    break;
            endswitch;
        }
        }
    }

    private function _bindPdoParam($array = array()){
        foreach($array as $f => $v){
            switch(gettype($array[$f])):
                case 'string':
                    $this->_oSTH->bindParam($f + 1, $array[$f], PDO::PARAM_STR);
                    break;
                case 'integer':
                    $this->_oSTH->bindParam($f + 1, $array[$f], PDO::PARAM_INT);
                    break;
                case 'boolean':
                    $this->_oSTH->bindParam($f + 1, $array[$f], PDO::PARAM_BOOL);
                    break;
            endswitch;
        }
    }

    public function error($msg){
        // log set as true
        if ( $this->log ) {
            // show executed query with error
            $this->showQuery();
            // die code
            $this->helper()->errorBox($msg);
        //} else {
            // show error message in log file
            file_put_contents( self::ERROR_LOG_FILE, date( 'Y-m-d h:m:s' ) . ' :: ' . $msg . "\n", FILE_APPEND );
            // die with user message
            $this->helper()->error();
        }
    }

    public function showQuery($logfile=false){
        if(!$logfile){
            echo "<div style='color:#990099; border:1px solid #777; padding:2px; background-color: #E5E5E5;'>";
            echo " Executed Query -> <span style='color:#008000;'> ";
            echo $this->helper()->formatSQL( $this->interpolateQuery() );
            echo "</span></div>";
            return $this;
        }else{
            // show error message in log file
            file_put_contents( self::SQL_LOG_FILE, date( 'Y-m-d h:m:s' ) . ' :: ' . $this->interpolateQuery() . "\n", FILE_APPEND );
            return $this;
        }
    }

    protected function interpolateQuery(){
        $sql = $this->_oSTH->queryString;
       if(!$this->batch){
        $params =((is_array($this->aData)) &&(count($this->aData) > 0)) ? $this->aData : $this->sSql;
        if(is_array($params)){
            foreach($params as $key => $value){
                if(strstr($key, ' ')){
                    $real_key = $this->getFieldFromArrayKey($key);
                    $params[$key] = is_string($value) ? '"' . $value . '"' : $value;
                    $keys[]       = is_string($real_key) ? '/:s_' . $real_key . '/' : '/[?]/';
                }else{
                    $params[$key] = is_string($value) ? '"' . $value . '"' : $value;
                    $keys[]       = is_string($key) ? '/:s_' . $key . '/' : '/[?]/';
                }
            }
            $sql = preg_replace($keys, $params, $sql, 1, $count);

            if(strstr($sql,':s_')){
                foreach($this->aWhere as $key => $value){
                    if(strstr($key, ' ')){
                        $real_key = $this->getFieldFromArrayKey($key);
                        $params[$key] = is_string($value) ? '"' . $value . '"' : $value;
                        $keys[]       = is_string($real_key) ? '/:s_' . $real_key . '/' : '/[?]/';
                    }else{
                        $params[$key] = is_string($value) ? '"' . $value . '"' : $value;
                        $keys[]       = is_string($key) ? '/:s_' . $key . '/' : '/[?]/';
                    }
                }
                $sql = preg_replace($keys, $params, $sql, 1, $count);
            }
            return $sql;
        } else{
            return $params;
        }
       }else{
           $params_batch =((is_array($this->aData)) &&(count($this->aData) > 0)) ? $this->aData : $this->sSql;
           $batch_query = '';
           if(is_array($params_batch)){
               foreach($params_batch as $keys => $params){
                   echo $params;
                   foreach($params as $key => $value){
                       if(strstr($key, ' ')){
                           $real_key = $this->getFieldFromArrayKey($key);
                           $params[$key] = is_string($value) ? '"' . $value . '"' : $value;
                           $array_keys[]       = is_string($real_key) ? '/:s_' . $real_key . '/' : '/[?]/';
                       }else{
                           $params[$key] = is_string($value) ? '"' . $value . '"' : $value;
                           $array_keys[]       = is_string($key) ? '/:s_' . $key . '/' : '/[?]/';
                       }
                   }
                   $batch_query .= "<br />".preg_replace($array_keys, $params, $sql, 1, $count);
               }
               return $batch_query;
           } else{
               return $params_batch;
           }
       }
    }

    public function getFieldFromArrayKey($array_key=array()){
        $key_array = explode(' ',$array_key);
        return(count($key_array)=='2') ? $key_array[0] :((count($key_array)> 2) ? $key_array[1] : $key_array[0]);
    }

    public function setErrorLog($mode = false){
        $this->log = $mode;
    }

}

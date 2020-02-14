<?php
class Database {
    
    public $dbname="demo";
    public $host="db.demo.com";
    public $port="1092";
    public $user="demo";
    public $password="demo";
    public $connection;
    private $db_info = array();
    
    function __construct() {
        $this->connection=mssql_connect("$this->host,$this->port",$this->user,$this->password);
        if(!$this->connection){
            echo 'Can Not Connect to Database!';
            exit();
        }else{
            if(!(mssql_select_db($this->dbname, $this->connection))){
                echo 'Can Not Select Database!';
                exit();
            }
        }
    }
   public function query($query){
           $qresult=mssql_query($query) or Die("\"$query\" FAILED");
           return $qresult;
   }
   public function getArray($query){
           $queryResult = $this->query($query);
           $i=0;
           while ($object = mssql_fetch_object($queryResult)){
               foreach($object as $key => $value) {
                   $resultArray[$i][$key]=$value; 
               }
           $i++;
           }
       return $resultArray;
   }
   public function numrows($query){
       return mssql_num_rows($this->query($query));
   }
   public function initSP($spName){
           return mssql_init($spName,$this->connection);
   }

   public function execSP($sp){
           mssql_execute($sp);
   }
   
   public function callSP($spName,$parameters=Array(),$outputType="Message"){//CALL Stored Procedure
           $sps=$this->InitSp($spName);
           $z=0;
           $outputparams=array();
           for($i=0;$i<count($parameters);$i++){
               $isOutput=$parameters[$i]["isOutput"];
               $isNull=$parameters[$i]["isNull"];
               $maxlen=$parameters[$i]["maxlen"];
               $paramName=$parameters[$i]["parameter"];
               $type=$parameters[$i]["type"];
               if($isOutput){
                   mssql_bind($sps,$paramName,$outputparams[$z],$type,$isOutput,$isNull);
                   $z++; 
               }else{
                   $variableToSend=$parameters[$i]["variable"];
                   mssql_bind($sps,$paramName,$variableToSend,$type);
               }
           }
           $this->executeSp($sps);
        return $outputparams; 
   }
   function __destruct() {
           mssql_close($this->connection);
   }

}
?> 
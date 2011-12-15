<?php

require_once ('../model/classes/ConfigurationManager.php');
require_once ('../model/classes/Errata.php');

define("ERROR_GENERIC", 9999);
define("ERROR_NO_BD", 0);
define("ERROR_NO_COMPLETE_OP", 1);
define("ERROR_INCORRECT_DATA", 2);

class ErrataException extends Exception {

}

function newErrata($errata) {

	connect();
	checkErrata($errata);
	
	$conf = new ConfigurationManager();
	
	$sql = "INSERT INTO " . $conf->__get("databasePrefix") . "errata SET ";
	
	$mandatoryProperties = Errata::getMandatoryProperties();
	$isFirst = true;	
	foreach($mandatoryProperties as $mp){
		if (!$isFirst){
			$sql .= ", ";
		}
		else{
			$isFirst = false;
		}
		
		$sql .= "`" . $mp ."` = '".$errata ->__get($mp)."'";
	}
	
	$optionalProperties = Errata::getOptionalProperties();
	foreach ($optionalProperties as $op) {
		$temp = $errata ->__get($op);
		if (isset($temp)) {
			$sql .= ", `". $op ."` = '" . $temp ."'";
		}
	}
	
	$sql .= ";";
	
	writeLog("newErrata","SQL = ".$sql);
	
	$res = mysql_query($sql);

	if ($res)
		return mysql_insert_id();

	throw new ErrataException("", ERROR_NO_COMPLETE_OP);

}

function checkErrata($errata) {
	$properties = Errata::getMandatoryProperties();

	foreach($properties as $property){
		$temp = $errata->__get($property);
		if (!isset($temp)){
			throw new ErrataException("",ERROR_INCORRECT_DATA);			
		}
	}
}

function connect() {	
	$conf = new ConfigurationManager();
	
	$con = mysql_connect($conf->__get("host"), $conf->__get("user"), $conf->__get("password"), true);
	if (!$con) {
		throw new ErrataException("", ERROR_NO_BD);
	}
	$db = mysql_select_db($conf->__get("database"), $con);
	if (!$db) {
		throw new ErrataException("", ERROR_NO_BD);
	}
}

function writeLog($function,$menssage){
    if (DEBUG_LOG){
        $time = getdate();
        $time = date('ymdHi',$time[0]);
        $logMessage = $time.": ".$function." - ".$menssage."\n";
        
        //TODO Cargar correctamente el log
        $stderr = fopen('../model/errata.log', 'a');
        fwrite($stderr,$logMessage);
        fflush($stderr); 
        fclose($stderr);
    }
}
?>
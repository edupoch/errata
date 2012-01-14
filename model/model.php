<?php

require_once ('../model/classes/ConfigurationManager.php');
require_once ('../model/classes/Errata.php');
require_once ('../model/util.php');

define("ERROR_GENERIC", 9999);
define("ERROR_NO_BD", 0);
define("ERROR_NO_COMPLETE_OP", 1);
define("ERROR_INCORRECT_DATA", 2);

define("FOLDER_ERRATA_CONTEXTS",'../admin/errataContexts/');

class ErrataException extends Exception {

}

function newErrata($errata) {

	connect();
	checkErrata($errata);
	
	$errataContextFile = udate('ymdHisu').".html";
	$errataContextPath = FOLDER_ERRATA_CONTEXTS.$errataContextFile;

	$html_code = stripslashes(html_entity_decode($errata->__get("html"),ENT_NOQUOTES,'UTF-8'));
	writeLog("newErrata","HTML code = ".$html_code);

	if ( ( $ecf = fopen($errataContextPath,"w") ) &&
		 ( fwrite($ecf, $html_code) == strlen( $html_code ) ) 
	   )
	{
		fclose($efc);

		$errata->__set("html",$errataContextFile);

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
	}

	if ($ecf){
		fclose($efc);
	}

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

function getErratas($view){

	$fixedValue = ($view == "fixed") ? 1 : 0;
	$deletedValue = ($view == "deleted") ? 1 : 0;
	
	connect();

	$conf = new ConfigurationManager();

	$sql = "SELECT * FROM " . $conf->__get("databasePrefix") . "errata
			WHERE fixed = ".$fixedValue." AND deleted = ".$deletedValue;
	$res = mysql_query($sql);
	$erratas = array();

	while($errata = mysql_fetch_object($res,"Errata"))
	  {
	  	array_push($erratas,$errata);
	  }

	return $erratas;  

}

function fixErrata($id,$unfix = false){
	
	return updateAttribute($id, "fixed", $unfix ? 0 : 1);

}

function deleteErrata($id, $undelete = false){
	
	return updateAttribute($id, "deleted", $undelete ? 0 : 1);

}

function updateAttribute($id, $attribute, $value){
	
	connect();

	$conf = new ConfigurationManager();

	$sql = "UPDATE " . $conf->__get("databasePrefix") . "errata 
			SET ".$attribute." = ".$value."
			WHERE id = ".$id;

	$res = mysql_query($sql);
	
	return $res;

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
        
        $stderr = fopen('../model/errata.log', 'a');
        fwrite($stderr,$logMessage);
        fflush($stderr); 
        fclose($stderr);
    }
}
?>
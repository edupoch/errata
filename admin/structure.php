<?php

require_once ('../model/model.php');

$page = $_GET["page"];

$pageTitle = _("Errata admin page");
require_once ('../admin/header.php');

$end = false;

while(!$end){
	try{
		require ('../admin/error.php');
		require_once ('../admin/'.$page);
		$end = true;
	}
	catch(Exception $e){		
		$error = $e->getMessage();
	}
}

require_once ('../admin/footer.php');

?>
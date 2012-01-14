<?php

$view = "";

$baseURL = "../admin/errataList.php";
$activeURL = $baseURL;
$fixedURL = $baseURL."?view=fixed";
$deletedURL = $baseURL."?view=deleted";

if (isset($_GET["view"])){
	$view = $_GET["view"];

	if ($view!="fixed" && $view!="deleted"){
		$view = "";
	}
}

?>

<?php
if (isset($_POST["op"]) && isset($_POST["id"])){
	$id = $_POST["id"];

	switch ($_POST["op"]){
		case "fix":
			if (fixErrata($id)){
				$message = _("The errata was succesfully marked as fixed");
			}
			break;
		case "delete":
			if (deleteErrata($id)){
				$message = _("The errata was succesfully deleted");
			}
			break;
		case "unfix":
			if (fixErrata($id,true)){
				$message = _("The errata was succesfully restored");
			}			
			break;
		case "undelete":
			if (deleteErrata($id,true)){
				$message = _("The errata was succesfully restored");
			}
			break;
	}
}

$erratas = getErratas($view);

?>


<div id="menu">
<a href="<?php echo $activeURL ?>"><?php echo _("Active erratas") ?></a>
<a href="<?php echo $fixedURL ?>"><?php echo _("Fixed erratas") ?></a>
<a href="<?php echo $deletedURL ?>"><?php echo _("Deleted erratas") ?></a>
</div>

<div><?php echo $message ?></div>

<?php

if (!$erratas || empty($erratas)){
?>
	<div><?php echo _("No erratas were found")?></div>

<?php 
} else{

?>

	<table border="1">
		<tr>
			<td><?php echo _("ID")?></td>
			<td><?php echo _("Date")?></td>	
			<td><?php echo _("Errata")?></td>
			<td><?php echo _("Correction")?></td>
			<td><?php echo _("IP")?></td>
			<td><?php echo _("URL")?></td>
			<td><?php echo _("Context")?></td>
			<td></td>
			<?php if (!$view) {?>
			<td></td>
			<?php } ?>
		</tr>

<?php

//TODO Group erratas by path (Same path, same errata)

	foreach($erratas as $errata){
?>
		<tr>
			<td><?php echo $errata->id ?></td>
			<td><?php echo $errata->date ?></td>
			<td><?php echo $errata->errata ?></td>
			<td><?php echo $errata->correction ?></td>
			<td><?php echo $errata->ip ?></td>
			<td><a href="<?php echo $errata->url ?>"><?php echo $errata->url ?></a></td>
			<td><a href="<?php echo FOLDER_ERRATA_CONTEXTS.$errata->html ?>"><?php echo $errata->html ?></a></td>
			<?php if (!$view) {?>
			<form name="input" action="../admin/errataList.php" method="post">
				<input type="hidden" name="id" id="id" value="<?php echo $errata->id ?>"/>
				<input type="hidden" name="op" id="op" value="fix"/>
				<td><input type="submit" value="<?php echo _("Mark as fixed")?>" /></td>
			</form>	
			<form name="input" action="../admin/errataList.php" method="post">
				<input type="hidden" name="id" id="id" value="<?php echo $errata->id ?>"/>
				<input type="hidden" name="op" id="op" value="delete"/>
				<td><input type="submit" value="<?php echo _("Delete it")?>" /></td>
			</form>			
			<?php } ?>
			<?php if ($view == "fixed") {?>
			<form name="input" action="../admin/errataList.php?view=fixed" method="post">
				<input type="hidden" name="id" id="id" value="<?php echo $errata->id ?>"/>
				<input type="hidden" name="op" id="op" value="unfix"/>
				<td><input type="submit" value="<?php echo _("Restore it")?>" /></td>
			</form>	
			<?php } ?>
			<?php if ($view == "deleted") {?>
			<form name="input" action="../admin/errataList.php?view=deleted" method="post">
				<input type="hidden" name="id" id="id" value="<?php echo $errata->id ?>"/>
				<input type="hidden" name="op" id="op" value="undelete"/>
				<td><input type="submit" value="<?php echo _("Restore it")?>" /></td>
			</form>		
			<?php } ?>
		</tr>

<?php 
	}
?>
	</table>

<?php 
}
?>	
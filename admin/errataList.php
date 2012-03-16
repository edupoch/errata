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

<header>
	<h1><?php echo $pageTitle ?></h1>
</header>

<section id="errata-list">

<div id="menu">
	<ul class="nav nav-tabs">
	  <li <?php if (!$view){ ?>class="active"<?php } ?> >
	    <a href="<?php echo $activeURL ?>"><?php echo _("Active erratas") ?></a>
	  </li>
	  <li <?php if ($view == "fixed"){ ?>class="active"<?php } ?> >
	  	<a href="<?php echo $fixedURL ?>"><?php echo _("Fixed erratas") ?></a>
	  </li>
	  <li <?php if ($view == "deleted"){ ?>class="active"<?php } ?>>
	  	<a href="<?php echo $deletedURL ?>"><?php echo _("Deleted erratas") ?></a>
	  </li>
	</ul>
</div>

<?php if ($message) { ?>
<div class="alert alert-success"><?php echo $message ?></div>
<?php } ?>

<?php

if (!$erratas || empty($erratas)){
?>
	<div class="well"><?php echo _("No erratas were found")?></div>

<?php 
} else{

?>

	<table class="table table-striped">
		<tr>
			<th><?php echo _("ID")?></th>
			<th><?php echo _("Date")?></th>	
			<th><?php echo _("Errata")?></th>
			<th><?php echo _("Correction")?></th>
			<th><?php echo _("IP")?></th>
			<th><?php echo _("URL")?></th>
			<th><?php echo _("Context")?></th>
			<th></th>
			<?php if (!$view) {?>
			<th></th>
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
				<td><button type="submit" class="btn btn-success"><i class="icon-check icon-white"></i> <?php echo _("Mark as fixed")?></button></td>
			</form>	
			<form name="input" action="../admin/errataList.php" method="post">
				<input type="hidden" name="id" id="id" value="<?php echo $errata->id ?>"/>
				<input type="hidden" name="op" id="op" value="delete"/>
				<td><button type="submit" class="btn btn-danger"><i class="icon-trash icon-white"></i> <?php echo _("Delete")?></button></td>
			</form>			
			<?php } ?>
			<?php if ($view == "fixed") {?>
			<form name="input" action="../admin/errataList.php?view=fixed" method="post">
				<input type="hidden" name="id" id="id" value="<?php echo $errata->id ?>"/>
				<input type="hidden" name="op" id="op" value="unfix"/>
				<td><button type="submit" class="btn btn-inverse"><i class="icon-repeat icon-white"></i> <?php echo _("Restore")?></button></td>
			</form>	
			<?php } ?>
			<?php if ($view == "deleted") {?>
			<form name="input" action="../admin/errataList.php?view=deleted" method="post">
				<input type="hidden" name="id" id="id" value="<?php echo $errata->id ?>"/>
				<input type="hidden" name="op" id="op" value="undelete"/>
				<td><button type="submit" class="btn btn-inverse"><i class="icon-repeat icon-white"></i> <?php echo _("Restore")?></button></td>
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

</section>
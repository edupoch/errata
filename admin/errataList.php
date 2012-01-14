<?php

$erratas = getErratas();

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
		</tr>

<?php

//TODO Group erratas by path (Same path, same errata)
//TODO Add errata management

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
		</tr>

<?php 
	}
?>
	</table>

<?php 
}
?>	
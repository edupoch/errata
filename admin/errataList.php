<?php

require_once ('../model/model.php');

//TODO Login required

$pageTitle = "Errata list";
require_once ('./header.php');

$erratas = getErratas();

if (!$erratas || empty($erratas)){
?>
	<div>No erratas were found</div>

<?php 
} else{

//TODO I18N it!

?>

	<table border="1">
		<tr>
			<td>ID</td>
			<td>Date</td>
			<td>Errata</td>
			<td>Correction</td>
			<td>URL</td>
		</tr>

<?php
	foreach($erratas as $errata){
?>

		<tr>
			<td><?php echo $errata->id ?></td>
			<td><?php echo $errata->date ?></td>
			<td><?php echo $errata->errata ?></td>
			<td><?php echo $errata->correction ?></td>
			<td><a href="<?php echo $errata->url ?>"><?php echo $errata->url ?></a></td>
		</tr>

<?php 
	}
?>
	</table>
<?php
}
require_once ('./footer.php');

?>
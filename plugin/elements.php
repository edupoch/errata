<?php

require_once("../model/util.php");
require_once("../model/classes/ConfigurationManager.php");

$conf = new ConfigurationManager();
setLang($conf->__get("lang"));

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type"/>
		<title>Errata HTML Elements</title>
		<link rel="stylesheet" type="text/css" href="elements.css" />
		<link rel="stylesheet" type="text/css" href="elementsDebug.css" />
	</head>
	<body>
		<div id='com-estudiocaravana-errata-box'>
			<a id="com-estudiocaravana-errata-title" href='javascript:errata.showForm()'><?php echo _("Errata report"); ?></a>
			<div id="com-estudiocaravana-errata-form">
				<?php echo _("Errata:")?> "<span id="com-estudiocaravana-errata-errata"></span>"
				<br>
				<?php echo _("Correction:")?>
				<input type="text" name="com-estudiocaravana-errata-correction" value="" id="com-estudiocaravana-errata-correction"/>
				<br>				
				<input type="hidden" name="com-estudiocaravana-errata-ipAddress" id="com-estudiocaravana-errata-ipAddress" value="<?php echo getIpAddress(); ?>" />
				<a href="javascript:errata.showDetails()"><?php echo _("+ More details")?></a>
				<br>
				<div id="com-estudiocaravana-errata-details">
					<?php echo _("Description:")?>
					<br>
					<textarea name="com-estudiocaravana-errata-errataDescription"></textarea><br>
					<?php echo _("Email:")?>
					<input type="text" name="com-estudiocaravana-errata-errataEmail" value="" id="com-estudiocaravana-errata-errataEmail"/>
					<br>
				</div>
				<a href="javascript:errata.sendErrata()"><?php echo _("Send errata report")?></a>
			</div>
			<div id="com-estudiocaravana-errata-status">
				<span id="com-estudiocaravana-errata-status-sendingErrata"><?php echo _("Sending errata...")?></span>
				<span id="com-estudiocaravana-errata-status-errataSent"><?php echo _("Errata sent!")?></span>
			</div>
		</div>
	</body>
</html>

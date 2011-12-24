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
		<div id='com-estudiocaravana-errata-errataBox'>
			<a id="com-estudiocaravana-errata-errataTitle" href='javascript:errata.showErrataForm()'><?php echo _("Errata report"); ?></a>
			<div id="com-estudiocaravana-errata-errataForm">
				<?php echo _("Errata:")?> "<span id="com-estudiocaravana-errata-errata"></span>"
				<br>
				<?php echo _("Correction:")?>
				<input type="text" name="com-estudiocaravana-errata-errataCorrection" value="" id="com-estudiocaravana-errata-errataCorrection"/>
				<br>
				<input type="hidden" name="com-estudiocaravana-errata-errataPath" id="com-estudiocaravana-errata-errataPath" value="" />
				<input type="hidden" name="com-estudiocaravana-errata-ipAddress" id="com-estudiocaravana-errata-ipAddress" value="<?php echo getIpAddress(); ?>" />
				<a href="javascript:errata.showErrataDetails()"><?php echo _("+ More details")?></a>
				<br>
				<div id="com-estudiocaravana-errata-errataDetails">
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

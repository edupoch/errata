<?php

require_once("../model/classes/ConfigurationManager.php");

$conf = new ConfigurationManager();
$lang = $conf->__get("lang");

if ($lang){
	setlocale(LC_MESSAGES, $lang);
	putenv("LANG=".$lang.".utf8");
	
	bindtextdomain("errata", "./locale");
	textdomain("errata");
	bind_textdomain_codeset("errata", 'UTF-8');
}

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
			<div id="com-estudiocaravana-errata-sendingErrata"><?php echo _("Sending errata...")?></div>
			<div id="com-estudiocaravana-errata-errataSent"><?php echo _("Errata sent!")?></div>			
		</div>
	</body>
</html>

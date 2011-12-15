<?php

require_once ('../model/model.php');
require_once ('../model/classes/Errata.php');

$mandatoryProperties = Errata::getMandatoryProperties();
$errata = new Errata();

foreach ($mandatoryProperties as $property) {
	if (isset($_POST[$property])) {
		$errata -> __set($property, $_POST[$property]);
	} else {
		throw new ErrataException("", ERROR_INCORRECT_DATA);
	}
}

$optionalProperties = Errata::getOptionalProperties();
foreach ($optionalProperties as $property) {
	if (isset($_POST[$property])) {
		$errata -> __set($property, $_POST[$property]);
	}
}

newErrata($errata);

?>
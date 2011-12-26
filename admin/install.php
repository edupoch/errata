<?php

require_once ('../model/model.php');

connect();

//TODO Ask the user for the database configuration 

$conf = new ConfigurationManager();

//TODO Should we delete this file after the installation?

$sql = "CREATE TABLE IF NOT EXISTS `" .$conf->__get("databasePrefix")."errata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `errata` varchar(50) CHARACTER SET utf8 NOT NULL,
  `correction` varchar(50) CHARACTER SET utf8 NOT NULL,
  `url` varchar(200) CHARACTER SET utf8 NOT NULL,
  `ip` varchar(20) CHARACTER SET utf8 NOT NULL,
  `path` varchar(200) CHARACTER SET utf8 NOT NULL,
  `html` varchar(30) CHARACTER SET utf8 NOT NULL,
  `description` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `fixed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci";

mysql_query($sql);

?>
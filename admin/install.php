<?php

$confFile = fopen("../model/conf.xml","r");

if ($confFile){

  throw new Exception(_("A configuration file was found. Please, remove it in order to continue with the installation process.")); 

}
else{
  if(isset($_POST, $_POST['action']) && $_POST['action']=='install'){

    if (isset($_POST["host"], $_POST["database"], $_POST["prefix"], $_POST["user"], $_POST["password"])){

      writeLog("install.php","Variables OK");
      
      $conf = new SimpleXMLElement("<configuration></configuration>");
      $conf->addChild("host",$_POST["host"]);
      $conf->addChild("database",$_POST["database"]);
      $conf->addChild("databasePrefix",$_POST["prefix"]);
      $conf->addChild("user",$_POST["user"]);
      $conf->addChild("password",$_POST["password"]);
      $conf->addChild("lang","es_ES");

      writeLog("install.php","Configuration variable successfully created");

      $confFile = fopen("../model/conf.xml","w");
      fwrite($confFile,$conf->asXML());
      fclose($confFile);
    }
    else{
      writeLog("install.php","Error: Missing one or more arguments");
    }

  connect();

  $conf = new ConfigurationManager();

  //TODO Should we delete this file after the installation process?

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

  }
  else{  
  ?>

  <form id="installForm" name="installForm" action="../admin/install.php" method="post">
    <input id="action" name="action" type="hidden" value="install"/>
    <label><?php echo _("Host:") ?></label><input id="host" name="host" type="text"/> <br>
    <label><?php echo _("Database name:") ?></label><input id="database" name="database" type="text"/> <br>
    <label><?php echo _("Table prefix:") ?></label><input id="prefix" name="prefix" type="text"/> <br>
    <label><?php echo _("User:") ?></label><input id="user" name="user" type="text"/> <br>
    <label><?php echo _("Password:") ?></label><input id="password" name="password" type="password"/> <br>  
    <input type="submit" value="<?php echo _("Submit") ?>"/>

  </form>
  <?php
  }
}
?>
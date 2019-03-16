<?php

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  logaccess($_SESSION['username'], "index.php", "Checking out the index.");

  $formVars['start'] = clean($_GET['start'], 10);
  if ($formVars['start'] == '') {
    $formVars['start'] = 'inventory';
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Mooks Manager</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main"> 

<div class="main">

<h4>Database Table Management</h4>

<p>This section contains all the supporting tables that are used to populate other areas in the database.<p>

<ul>
  <li><a href="<?php print $Dataroot; ?>/add.active.php">Manage Active Skills table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.adept.php">Manage Adept table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.agent.php">Manage Agent table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.ammo.php">Manage Ammunition table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.armor.php">Manage Armor table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.bioware.php">Manage Bioware table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.commlink.php">Manage Commlink table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.contacts.php">Manage Contacts table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.cyberdeck.php">Manage CyberDeck table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.cyberware.php">Manage Cyberware table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.firearm.php">Manage Firearms table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.gear.php">Manage Gear table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.knowledge.php">Manage Knowledge Skills table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.language.php">Manage Language Skills table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.lifestyle.php">Manage Lifestyle table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.melee.php">Manage Melee Weapons table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.mentor.php">Manage Mentor Spirits table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.metatypes.php">Manage Metatypes table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.program.php">Manage Programs table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.qualities.php">Manage Qualities table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.spells.php">Manage Spells table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.tradition.php">Manage Tradition table</a></li>
  <li><a href="<?php print $Dataroot; ?>/add.vehicles.php">Manage Vehicles table</a></li>
</ul>

</div>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>

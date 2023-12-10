<?php

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Shadowrunner);

  logaccess($db, $_SESSION['username'], "index.php", "Checking out the index.");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Account Management</title>

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

<h4>Account Management</h4>

<p>This section contains all the account management tables.</p>

<ul>
<?php
  if (isset($_SESSION['username'])) {
?>
  <li><a href="<?php print $Usersroot;  ?>/users.php">Account Details</a></li>
  <li><a href="<?php print $Bugroot; ?>/bugs.php">Report a Bug</a></li>
  <li><a href="<?php print $Featureroot; ?>/features.php">Request Enhancement</a></li>
  <li><a href="<?php print $FAQroot; ?>/answers.php">Ask a Question (FAQ)</a></li>
  <li><a href="<?php print $FAQroot; ?>/whatsnew.php">What's New With Inventory 3.0?</a></li>
  <li><a href="<?php print $Loginroot; ?>/logout.php">Logout (<?php print $_SESSION['username']; ?>)</a></li>
<?php
    if (check_userlevel($db, $AL_Johnson)) {
?>
</ul>

<ul>
  <li><a href="<?php print $Usersroot; ?>/add.users.php">User Management</a></li>
  <li><a href="<?php print $Usersroot; ?>/add.groups.php">Group Management</a></li>
  <li><a href="<?php print $Usersroot; ?>/add.levels.php">Access Level Management</a></li>
  <li><a href="<?php print $Reportroot;  ?>/logs.php">View Month's Logs</a></li>
</ul>

<?php
  }
} else {
?>
  <li><a href="<?php print $Loginroot; ?>/login.php">Login</a></li>
<?php
}
?>
</ul>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>

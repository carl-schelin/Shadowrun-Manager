<?php
  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  logaccess($_SESSION['username'], "index.php", "Checking out the index.");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Matrix Description</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );
});

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<div id="tabs">

<ul>
  <li><a href="#hacking">Hacking</a></li>
  <li><a href="#cyber">Cybercombat</a></li>
</ul>

<div id="hacking">

<p>Starts SR5 - Page </p>
<p></p>
<ol>
  <li></li>
</ol>


<p>Hacking SR4 236</p>
<ul>
  <li>Exploit (pg: )</li>
  <li>Stealth (pg: )</li>
  <li>Hacking (pg: )</li>
  <li>Extended TEst (pg: )</li>
  <li>Personal, Security, Admin (pg: )</li>
</ul>

<p>Node Info</p>
<ul>
  <li>Analyze (pg: )</li>
  <li>System (pg: )</li>
  <li>Firewall? (pg: )</li>
</ul>

</div>


<div id="cyber">

<p>Starts SR5 - Page </p>
<p></p>
<ol>
  <li></li>
</ol>


<p>Matrix Combat</p>
<ul>
  <li>Attacker: Icon or Digital (pg: )</li>
  <li>Attack Program Rating (pg: )</li>
  <li>Black IC? (pg: )</li>
  <li>Cybercombat (pg: )</li>
  <li>matrix Wound Modifier (pg: )</li>
  <li>Simsense Link, Cold/Hot (pg: )</li>
</ul>

<p>Defender</p>
<ul>
  <li>Response (pg: )</li>
  <li>Firewall (pg: )</li>
  <li>Full Defense (pg: )</li>
  <li>Hacking (pg: )</li>
  <li>Simsinse Link hot/cold (pg: )</li>
  <li>Matrix Wound Modifier (pg: )</li>
  <li>Soak System (pg: )</li>
  <li>Soak Armor (pg: )</li>
</ul>

</div>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>

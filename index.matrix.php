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

<script type="text/javascript">

$(document).ready( function() {
  $( "#tabs"        ).tabs( ).addClass( "tab-shadow" );
  $( "#hackingtabs" ).tabs( ).addClass( "tab-shadow" );
  $( "#cybertabs"   ).tabs( ).addClass( "tab-shadow" );
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

<div id="hackingtabs">

<ul>
  <li><a href="#hck1st">1st Edition</a></li>
  <li><a href="#hck2nd">2nd Edition</a></li>
  <li><a href="#hck3rd">3rd Edition</a></li>
  <li><a href="#hck4th">4th Edition</a></li>
  <li><a href="#hck5th">5th Edition</a></li>
  <li><a href="#hck6th">6th Edition</a></li>
</ul>


<div id="hck1st">

<p>First</p>

</div>


<div id="hck2nd">

<p>Second</p>

</div>


<div id="hck3rd">

<p>Third</p>

</div>


<div id="hck4th">

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


<div id="hck5th">

<p>Fifth</p>

</div>


<div id="hck6th">

<p>Sixth</p>

</div>


</div>

</div>


<div id="cyber">

<div id="cybertabs">

<ul>
  <li><a href="#cbr1st">1st Edition</a></li>
  <li><a href="#cbr2nd">2nd Edition</a></li>
  <li><a href="#cbr3rd">3rd Edition</a></li>
  <li><a href="#cbr4th">4th Edition</a></li>
  <li><a href="#cbr5th">5th Edition</a></li>
  <li><a href="#cbr6th">6th Edition</a></li>
</ul>


<div id="cbr1st">

<p>First</p>

</div>


<div id="cbr2nd">

<p>Second</p>

</div>


<div id="cbr3rd">

<p>Third</p>

</div>


<div id="cbr4th">

<p>Fourth</p>

</div>


<div id="cbr5th">

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


<div id="cbr6th">

<p>Sixth</p>

</div>


</div>

</div>


</div>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>

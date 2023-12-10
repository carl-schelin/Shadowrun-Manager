<?php
# Script: add.orphan.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.orphan.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Orphans</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">
<?php

  if (check_userlevel($db, $AL_Johnson)) {
?>
function delete_orphan( p_script_url ) {
  var answer;

  answer = confirm('Delete this Orphan?');

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.orphan.mysql.php');
  }
}
<?php
  }
?>

function clear_fields() {
  show_file('add.orphan.mysql.php');
}

$(document).ready( function() {
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . "/topmenu.start.php"); ?>
<?php include($Sitepath . "/topmenu.end.php"); ?>

<div id="main">

<form name="class">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Orphan Review</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('orphan-help');">Help</a></th>
</tr>
</table>

<div id="orphan-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Active Skill Form</strong>
  <ul>
    <li><strong>Type</strong> - Tyep type of the active skill.</li>
    <li><strong>Name</strong> - The name of the skill.</li>
    <li><strong>Group</strong> - The group of the skill.</li>
    <li><strong>Attribute</strong> - The linked attribute for the skill.</li>
    <li><strong>Default</strong> - Whether the skill can be defaulted (-1).</li>
    <li><strong>Book</strong> - Select the book where this skill is located.</li>
    <li><strong>Page</strong> - Identify the page number.</li>
  </ul></li>
</ul>

</div>

</div>

</form>


<span id="orphan_table"><?php print wait_Process('Loading Orphans...')?></span>

</div>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>

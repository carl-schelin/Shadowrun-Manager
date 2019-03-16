<?php
# Script: contacts.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  include($Sitepath . '/guest.php');

  $package = "contacts.php";

  logaccess($formVars['username'], $package, "Accessing the script");

  if (isset($_GET["sort"])) {
    $formVars['sort'] = clean($_GET["sort"], 20);
    $orderby = "order by " . $formVars['sort'] . $_SESSION['sort'];
    if ($_SESSION['sort'] == ' desc') {
      $_SESSION['sort'] = '';
    } else {
      $_SESSION['sort'] = ' desc';
    }
  } else {
    $orderby = "order by inv_name";
    $_SESSION['sort'] = '';
  }

  if (isset($_GET['id'])) {
    $formVars['group'] = clean($_GET['id'], 10);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Contact Listing</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">

$(document).ready( function () {
});

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<form name="contacts">

<div class="main">

<table class="ui-styled-table" width="100%">
<tr>
<?php

  print "  <th class=\"ui-state-default\">Group Member Listing</th>";

  print "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('help');\">Help</a></th>\n";
  print "</tr>\n";
  print "</table>\n";

  print "<div id=\"help\" style=\"display:none\">\n\n";

  print "<div class=\"main-help ui-widget-content\">\n\n";

  print "<p>The Active Server display uses two colors to clearly identify what the current status is of that device:</p>\n\n";

  print "</div>\n";

  print "</div>\n\n";

  print "<table class=\"ui-styled-table\" width=\"100%\">\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\">Owner Name</th>\n";
  print "  <th class=\"ui-state-default\">Contact Name</th>\n";
  print "  <th class=\"ui-state-default\">Archetype</th>\n";
  print "  <th class=\"ui-state-default\">Book/Page</th>\n";
  print "</tr>\n";

  $q_string  = "select con_id,con_name,con_archetype,con_book,con_page,usr_first,usr_last ";
  $q_string .= "from contact ";
  $q_string .= "left join users on users.usr_id = contact.con_owner ";
  $q_string .= "order by con_name ";
  $q_contact = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_contact) > 0) {
    while ($a_contact = mysql_fetch_array($q_contact)) {

      $linkstart = "<a href=\"" . $Showroot . "/mooks.php?id=" . $a_members['mem_runner'] . "\" target=\"_blank\">";
      $linkend = "</a>";

      $bookpage = $a_contact['con_book'] . "/" . $a_contact['con_page'];
      if ($a_contact['con_page'] == 0) {
        $bookpage = '';
      }
      

      $class = "ui-widget-content";

      print "<tr>\n";
      print "  <td class=\"" . $class . " delete\">"              . $a_contact['usr_first']  . " " . $a_contact['usr_last']            . "</td>\n";
      print "  <td class=\"" . $class . "\">"        . $linkstart . $a_contact['con_name']                                 . "</td>\n";
      print "  <td class=\"" . $class . "\">"                     . $a_contact['con_archetype']                                 . "</td>\n";
      print "  <td class=\"" . $class . "\">"                     . $bookpage . "</td>\n";
      print "</tr>\n";
    }
  } else {
    print "<tr>\n";
    print "  <td class=\"ui-widget-content\" colspan=\"2\">No Groups to display.</td>\n";
    print "</tr>\n";
  }

?>
</table>

</div>

</form>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
